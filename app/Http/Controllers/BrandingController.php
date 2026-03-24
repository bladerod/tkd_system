<?php

namespace App\Http\Controllers;

use App\Models\Branding;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class BrandingController extends Controller
{
    public function index()
    {
        $branding = Branding::getBranding();
        return view('branding', compact('branding'));
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'certificate_header_text' => 'nullable|string|max:255',
            'certificate_signature_name' => 'nullable|string|max:255',
            'signature_position' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'official_seal' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $branding = Branding::first();
        
        if (!$branding) {
            $branding = new Branding();
        }

        // Handle Logo Upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($branding->logo_path) {
                Storage::delete($branding->logo_path);
            }
            
            $logoPath = $request->file('logo')->store('branding/logos', 'public');
            $branding->logo_path = $logoPath;
            $branding->logo_filename = $request->file('logo')->getClientOriginalName();
        }

        // Handle Official Seal Upload
        if ($request->hasFile('official_seal')) {
            // Delete old seal if exists
            if ($branding->official_seal_path) {
                Storage::delete($branding->official_seal_path);
            }
            
            $sealPath = $request->file('official_seal')->store('branding/seals', 'public');
            $branding->official_seal_path = $sealPath;
            $branding->official_seal_filename = $request->file('official_seal')->getClientOriginalName();
        }

        // Update text fields
        $branding->certificate_header_text = $request->certificate_header_text;
        $branding->certificate_signature_name = $request->certificate_signature_name;
        $branding->signature_position = $request->signature_position;
        
        $branding->save();

        return redirect()->route('settings.branding')
            ->with('success', 'Branding settings updated successfully!');
    }
}