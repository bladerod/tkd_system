<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\CertificateTemplate;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CertificateController extends Controller
{
    /* ================= PAGE ================= */
    public function index()
    {
        $certificates = Certificate::with('student','template')->latest()->get();
        return view('certificates.index', compact('certificates'));
    }

    /* ================= API ================= */

    public function getStudents()
    {
        return Student::select('id', DB::raw("CONCAT(fname,' ',lname) as name"))->get();
    }

    public function getTemplates()
    {
        return CertificateTemplate::select('id','name','type')->get();
    }

    /* ================= GENERATE ================= */

    public function generate(Request $request)
    {
        $student = Student::find($request->student_id);

        $cert = Certificate::create([
            'student_id' => $student->id,
            'template_id' => $request->template_id,
            'certificate_type' => 'promotion',
            'title' => 'Belt Promotion',
            'issued_date' => now(),

            'data' => json_encode([
                'student_name' => $student->fname . ' ' . $student->lname,
                'belt_level' => $request->belt_level ?? 'Yellow Belt',
                'date_issued' => now()->format('F d, Y')
            ]),

            'qr_code_value' => 'CERT-' . uniqid()
        ]);

        return response()->json(['success'=>true]);
    }

    /* ================= PREVIEW ================= */

    public function preview(Request $request)
    {
        $template = CertificateTemplate::find($request->template_id);

        $layout = $template->layout;

        $cert = (object)[
            'template' => (object)['layout' => $layout],
            'data' => [
                'student_name' => 'Juan Dela Cruz',
                'belt_level' => $request->belt_level ?? 'Yellow Belt'
            ],
            'qr_code_value' => 'PREVIEW'
        ];

        return view('certificates.preview', compact('cert'));
    }

    /* ================= VIEW ================= */

    public function show($id)
    {
        $cert = Certificate::with('template')->findOrFail($id);
        return view('certificates.preview', compact('cert'));
    }

    /* ================= PDF ================= */

    public function download($id)
    {
        $cert = Certificate::with('template')->findOrFail($id);

        $pdf = Pdf::loadView('certificates.pdf', compact('cert'))
            ->setPaper('a4','landscape');

        return $pdf->download('certificate.pdf');
    }

    /* ================= VERIFY ================= */

    public function verify($code)
    {
        $cert = Certificate::where('qr_code_value',$code)->firstOrFail();
        return view('certificates.verify', compact('cert'));
    }

    /* ================= TEMPLATE LIST ================= */
public function templates()
{
    $templates = CertificateTemplate::all();
    return view('templates.index', compact('templates'));
}

/* ================= CREATE PAGE ================= */
public function createTemplate()
{
    return view('templates.create');
}

/* ================= STORE ================= */
public function storeTemplate(Request $request)
{
    CertificateTemplate::create([
        'name' => $request->name,
        'type' => $request->type,

        // IMPORTANT: TEXT field
        'layout' => json_encode([
            "student_name" => ["x"=>300,"y"=>250],
            "belt_level" => ["x"=>300,"y"=>320]
        ])
    ]);

    return redirect('/templates');
}

/* ================= EDITOR ================= */
public function editor($id)
{
    $template = CertificateTemplate::findOrFail($id);
    return view('templates.editor', compact('template'));
}

/* ================= SAVE LAYOUT ================= */
public function saveLayout(Request $request, $id)
{
    $template = CertificateTemplate::findOrFail($id);

    $template->layout = json_encode($request->layout);
    $template->save();

    return response()->json([
        'success' => true
    ]);
}

/* ================= UPLOAD BACKGROUND ================= */
public function uploadBackground(Request $request, $id)
{
    $template = CertificateTemplate::findOrFail($id);

    $file = $request->file('background');
    $path = $file->store('templates', 'public');

    $template->background = $path;
    $template->save();

    return response()->json(['path'=>$path]);
}

public function uploadImage(Request $request)
{
    $file = $request->file('image');
    $path = $file->store('templates', 'public');

    return response()->json([
        'path' => $path
    ]);
}
}
