<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Certificate;
use Illuminate\Support\Facades\DB;

class CertificateController extends Controller
{
    // VIEW PAGE
    public function index()
    {
        $certificates = DB::table('certificates')
            ->join('students', 'students.id', '=', 'certificates.student_id')
            ->select(
                'certificates.id',
                'certificates.certificate_type as type',
                'certificates.title',
                'certificates.issued_date as date',
                'certificates.qr_code_value as qr_code',
                'certificates.verification_url',
                DB::raw("CONCAT(students.first_name, ' ', students.last_name) as student_name"),
                DB::raw("IF(certificates.qr_code_value IS NOT NULL, 1, 0) as has_qr")
            )
            ->get();

        return view('certificates.index', compact('certificates'));
    }

    // GET STUDENTS
    public function getStudents()
    {
        $students = DB::table('students')
            ->select(
                'id',
                DB::raw("CONCAT(first_name, ' ', last_name) as name"),
                'current_belt as belt'
            )
            ->get();

        return response()->json($students);
    }

    // GENERATE CERTIFICATE
    public function generate(Request $request)
    {
        $qr = uniqid('CERT-');

        $cert = Certificate::create([
            'student_id' => $request->student_id,
            'certificate_type' => $request->type,
            'title' => $request->title,
            'description' => $request->description,
            'issued_date' => now(),
            'issued_by_user_id' => auth()->id() ?? 7,
            'qr_code_value' => $qr,
            'verification_url' => url("/verify/$qr"),
            'pdf_path' => null
        ]);

        return response()->json([
            'success' => true,
            'id' => $cert->id
        ]);
    }

    // VIEW CERTIFICATE DETAILS
    public function show($id)
{
    $cert = Certificate::with(['student', 'issuer'])->findOrFail($id);

    $student = $cert->student;

    return response()->json([
        'id' => $cert->id,
        'type' => $cert->certificate_type,
        'title' => $cert->title,
        'description' => $cert->description,
        'issued_date' => $cert->issued_date,
        'issued_by' => trim(optional($cert->issuer)->fname . ' ' . optional($cert->issuer)->lname),
        'qr_code' => $cert->qr_code_value,
        'verification_url' => $cert->verification_url,
        'pdf_url' => $cert->pdf_path ? asset('storage/' . $cert->pdf_path) : null,

        'student' => [
            'name' => $student
                ? ($student->first_name . ' ' . $student->last_name)
                : 'Unknown Student',

            'belt' => $student->current_belt ?? 'N/A',
            'photo' => $student->photo_url ?? null
        ]
    ]);
}

    // EMAIL (MOCK)
    public function email($id)
    {
        return response()->json([
            'success' => true
        ]);
    }

    // BULK GENERATE
    public function bulkGenerate(Request $request)
    {
        $students = DB::table('belt_exam_results')
            ->where('exam_id', $request->exam_id)
            ->where('result', 'pass')
            ->get();

        $count = 0;

        foreach ($students as $s) {
            Certificate::create([
                'student_id' => $s->student_id,
                'certificate_type' => 'belt_promotion',
                'title' => 'Belt Promotion Certificate',
                'issued_date' => now(),
                'issued_by_user_id' => auth()->id() ?? 7,
                'qr_code_value' => uniqid('CERT-'),
                'verification_url' => url('/verify/' . uniqid()),
            ]);
            $count++;
        }

        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }

    // DOWNLOAD
    public function download($id)
    {
        $cert = Certificate::findOrFail($id);

        if (!$cert->pdf_path) {
            return abort(404, 'No PDF found');
        }

        return response()->download(storage_path('app/public/' . $cert->pdf_path));
    }

    // PRINT
    public function print(Request $request)
{
    $ids = explode(',', $request->ids);

    $certs = Certificate::with('student') // ✅ IMPORTANT
        ->whereIn('id', $ids)
        ->get();

    return view('certificates.print', compact('certs'));
}
}
