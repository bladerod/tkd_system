<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
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
        return view('certificates.index', compact('certificates'));
    }

    /* ================= API ================= */
 public function getStudents()
    {
        // 1. Fetch the data using the logic you provided
        $students = Student::select(
            'id',
            DB::raw("CONCAT(first_name, ' ', last_name) as name")
        )->get();

        // 2. Return as JSON response (Laravel does this automatically if you return a collection)
        return response()->json($students);
    }

    /* ================= CREATE (GENERATE) ================= */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'certificate_type' => 'required',
            'title' => 'required'
        ]);

        $student = Student::findOrFail($request->student_id);

        $qrCode = 'CERT-' . Str::uuid();
        $verifyUrl = url('/verify/' . $qrCode);

        $cert = Certificate::create([
            'student_id' => $student->id,
            'certificate_type' => $request->certificate_type,
            'title' => $request->title,
            'description' => $request->description ?? null,
            'issued_date' => now(),
            'issued_by_user_id' => auth()->id(), // IMPORTANT
            'qr_code_value' => $qrCode,
            'verification_url' => $verifyUrl,
            'pdf_path' => null
        ]);

        return response()->json([
            'success' => true,
            'data' => $cert
        ]);
    }

    /* ================= READ ================= */
    public function show($id)
    {
        $cert = Certificate::with('student')->findOrFail($id);
        return view('certificates.preview', compact('cert'));
    }

    /* ================= DELETE ================= */
    public function destroy($id)
    {
        $cert = Certificate::findOrFail($id);
        $cert->delete();

        return response()->json([
            'success' => true
        ]);
    }

    /* ================= PDF ================= */
    public function download($id)
    {
        $cert = Certificate::with('student')->findOrFail($id);

        $pdf = Pdf::loadView('certificates.pdf', compact('cert'))
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
