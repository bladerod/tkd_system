<?php

namespace App\Http\Controllers;

use App\Models\CertificateTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TemplateController extends Controller
{
    /* ══════════════════════════════════════════
     |  INDEX — List all templates
     ══════════════════════════════════════════ */
    public function index()
    {
        $templates = CertificateTemplate::latest()->get();

        return view('template.index', compact('templates'));
    }

    /* ══════════════════════════════════════════
     |  CREATE — Show create form
     ══════════════════════════════════════════ */
    public function create()
    {
        return view('template.create');
    }

    /* ══════════════════════════════════════════
     |  STORE — Save new template
     ══════════════════════════════════════════ */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:promotion,competition,dan,achievement',
        ]);

        $template = CertificateTemplate::create([
            'name' => $request->name,
            'type' => $request->type,
            'status' => 'draft', // Optional, if you have a status column
            'layout' => json_encode(['objects' => []]), // Empty canvas layout
        ]);

        // Redirect straight into the editor!
        return redirect()->route('templates.editor', $template->id);
    }

    /* ══════════════════════════════════════════
     |  EDITOR — Show the canvas editor
     ══════════════════════════════════════════ */
    public function editor($id)
    {
        $template = CertificateTemplate::findOrFail($id);

        // Ensure layout is always a decoded array for Blade @json
        if (is_string($template->layout)) {
            $template->layout = json_decode($template->layout, true) ?? ['objects' => []];
        }

        return view('template.editor', compact('template'));
    }

    /* ══════════════════════════════════════════
     |  SAVE LAYOUT — Ajax save from editor
     ══════════════════════════════════════════ */
    public function saveLayout(Request $request, $id)
    {
        $request->validate([
            'layout'         => 'required|array',
            'layout.objects' => 'required|array',
        ]);

        $template = CertificateTemplate::findOrFail($id);

        $template->layout = json_encode($request->layout);
        $template->save();

        return response()->json([
            'success' => true,
            'message' => 'Layout saved successfully.',
        ]);
    }

    /* ══════════════════════════════════════════
     |  PREVIEW — Return preview view or JSON
     ══════════════════════════════════════════ */
    public function preview($id)
    {
        $template = CertificateTemplate::findOrFail($id);

        if (is_string($template->layout)) {
            $template->layout = json_decode($template->layout, true) ?? ['objects' => []];
        }

        // If AJAX / API request, return JSON
        if (request()->expectsJson()) {
            return response()->json([
                'template' => $template,
            ]);
        }

        return view('template.preview', compact('template')); // ERROR HERE
    }

    /* ══════════════════════════════════════════
     |  CLONE — Duplicate a template
     ══════════════════════════════════════════ */
    public function clone($id)
    {
        $original = CertificateTemplate::findOrFail($id);

        $clone = $original->replicate();
        $clone->name   = $original->name . ' (Copy)';
        $clone->status = 'draft';
        $clone->save();

        return response()->json([
            'success'  => true,
            'message'  => 'Template cloned.',
            'redirect' => route('templates.editor', $clone->id),
        ]);
    }

    /* ══════════════════════════════════════════
     |  DESTROY — Delete a template
     ══════════════════════════════════════════ */
    public function destroy($id)
    {
        $template = CertificateTemplate::findOrFail($id);
        $template->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Template deleted.']);
        }

        return redirect()->route('templates.index')
                         ->with('success', 'Template deleted.');
    }

    /* ══════════════════════════════════════════
     |  UPLOAD IMAGE — Store image asset for canvas
     ══════════════════════════════════════════ */
    public function uploadImage(Request $request, $id)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,svg|max:4096',
        ]);

        $template  = CertificateTemplate::findOrFail($id);
        $filename  = Str::uuid() . '.' . $request->file('image')->getClientOriginalExtension();
        $path      = $request->file('image')->storeAs('templates', $filename, 'public');

        return response()->json([
            'success'  => true,
            'filename' => $filename,
            'url'      => Storage::url($path),
        ]);
    }
}
