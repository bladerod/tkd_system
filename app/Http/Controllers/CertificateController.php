<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Certificate;
use App\Models\CertificateTemplate;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CertificateController extends Controller
{
    /* ================= PAGE ================= */
    public function index()
    {
        $certificates = Certificate::with('student')->latest()->get();
        $branches = Branch::all();
        $students = Student::all();
        return view('certificates.index', compact('certificates', 'students', 'branches'));
        
    }

    /* ================= API ================= */
    public function getStudents()
    {
        return Student::select(
            'id',
            DB::raw("CONCAT(first_name, ' ', last_name) as name")
        )->get();
    }

    public function getTemplates()
    {
        return CertificateTemplate::select('id','name','type','layout','background')->get();
    }

    /* ================= CREATE ================= */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'template_id' => 'required|exists:certificate_templates,id',
        ]);

        $student = Student::findOrFail($request->student_id);
        $template = CertificateTemplate::findOrFail($request->template_id);

        $qrCode = 'CERT-' . Str::uuid();

        $cert = Certificate::create([
            'student_id' => $student->id,
            'certificate_type' => $template->type,
            'title' => $template->name,
            'description' => $request->description ?? null,
            'issued_date' => now(),
            'issued_by_user_id' => auth()->id(),
            'qr_code_value' => $qrCode,
            'verification_url' => url('/verify/'.$qrCode),
            'pdf_path' => null
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    /* ================= VIEW ================= */
    public function show($id)
    {
        $cert = Certificate::with('student')->findOrFail($id);
        return view('certificates.preview', compact('cert'));
    }

    /* ================= DELETE ================= */
    public function destroy($id)
    {
        Certificate::findOrFail($id)->delete();
        return response()->json(['success'=>true]);
    }

    /* ================= PDF (DYNAMIC TEMPLATE) ================= */
    public function download($id)
    {
        $cert = Certificate::with('student')->findOrFail($id);

        $template = CertificateTemplate::where('name', $cert->title)->first();

        $layout = json_decode($template->layout, true);

        $pdf = Pdf::loadView('certificates.pdf', compact('cert','template','layout'))
            ->setPaper('a4','landscape');

        return $pdf->download('certificate-'.$cert->id.'.pdf');
    }

    /* ================= VERIFY ================= */
    public function verify($code)
    {
        $cert = Certificate::where('qr_code_value',$code)
            ->with('student')
            ->firstOrFail();

        return view('certificates.verify', compact('cert'));
    }
}
