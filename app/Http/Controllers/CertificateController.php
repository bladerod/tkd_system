<?php
// app/Http/Controllers/CertificateController.php
namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Student;
use App\Models\BeltExamResult;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificateController extends Controller
{
    public function index()
    {
        $certificates = Certificate::with(['student', 'issuedBy'])
            ->orderBy('issued_date', 'desc')
            ->get()
            ->map(function($cert) {
                return [
                    'id' => $cert->id,
                    'student_name' => $cert->student->first_name . ' ' . $cert->student->last_name,
                    'student_id' => $cert->student_id,
                    'type' => $cert->certificate_type,
                    'title' => $cert->title,
                    'date' => $cert->issued_date,
                    'qr_code' => $cert->qr_code_value,
                    'has_qr' => !empty($cert->qr_code_value),
                    'pdf_path' => $cert->pdf_path,
                    'issued_by' => $cert->issuedBy?->name ?? 'System',
                    'verification_url' => $cert->verification_url
                ];
            });

        return view('certificate.index', compact('certificates'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'type' => 'required|in:belt_promotion,competition,participation,achievement',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $student = Student::findOrFail($request->student_id);

        // Generate unique QR code
        $qrCode = 'TKD-' . strtoupper(Str::random(8));
        $verificationUrl = route('certificates.verify', $qrCode);

        // Create certificate
        $certificate = Certificate::create([
            'student_id' => $request->student_id,
            'certificate_type' => $request->type,
            'title' => $request->title,
            'description' => $request->description,
            'issued_date' => now(),
            'issued_by_user_id' => auth()->id(),
            'qr_code_value' => $qrCode,
            'verification_url' => $verificationUrl
        ]);

        // Generate PDF
        $pdf = $this->generatePDF($certificate);
        $pdfPath = 'certificates/' . $qrCode . '.pdf';

        // Store PDF
        \Storage::disk('public')->put($pdfPath, $pdf->output());
        $certificate->update(['pdf_path' => $pdfPath]);

        return response()->json([
            'success' => true,
            'certificate' => $certificate->load(['student', 'issuedBy']),
            'download_url' => asset('storage/' . $pdfPath)
        ]);
    }

    public function show($id)
    {
        $certificate = Certificate::with(['student', 'issuedBy'])->findOrFail($id);

        return response()->json([
            'id' => $certificate->id,
            'student' => [
                'name' => $certificate->student->first_name . ' ' . $certificate->student->last_name,
                'belt' => $certificate->student->current_belt,
                'photo' => $certificate->student->photo_url
            ],
            'type' => $certificate->certificate_type,
            'title' => $certificate->title,
            'description' => $certificate->description,
            'issued_date' => $certificate->issued_date,
            'issued_by' => $certificate->issuedBy?->name,
            'qr_code' => $certificate->qr_code_value,
            'verification_url' => $certificate->verification_url,
            'pdf_url' => $certificate->pdf_path ? asset('storage/' . $certificate->pdf_path) : null
        ]);
    }

    public function download($id)
    {
        $certificate = Certificate::findOrFail($id);

        if (!$certificate->pdf_path || !\Storage::disk('public')->exists($certificate->pdf_path)) {
            // Regenerate if missing
            $pdf = $this->generatePDF($certificate);
            return $pdf->download($certificate->qr_code_value . '.pdf');
        }

        return response()->file(
            storage_path('app/public/' . $certificate->pdf_path),
            ['Content-Disposition' => 'inline; filename="' . $certificate->qr_code_value . '.pdf"']
        );
    }

    public function print($id)
    {
        $certificate = Certificate::with(['student', 'issuedBy'])->findOrFail($id);

        return view('certificates.print', compact('certificate'));
    }

    public function email($id)
    {
        $certificate = Certificate::with(['student.primaryParent.user'])->findOrFail($id);

        $parentEmail = $certificate->student->primaryParent?->user?->email;

        if (!$parentEmail) {
            return response()->json(['error' => 'No parent email found'], 400);
        }

        // Send email with certificate attachment
        \Mail::to($parentEmail)->send(new \App\Mail\CertificateMail($certificate));

        return response()->json(['success' => true, 'message' => 'Certificate emailed successfully']);
    }

    public function verify($qrCode)
    {
        $certificate = Certificate::with('student')->where('qr_code_value', $qrCode)->first();

        if (!$certificate) {
            return view('certificates.verify-invalid');
        }

        return view('certificates.verify', compact('certificate'));
    }

    public function getStudentsForDropdown()
    {
        $students = Student::where('status', 'active')
            ->select('id', 'first_name', 'last_name', 'current_belt', 'student_code')
            ->get()
            ->map(fn($s) => [
                'id' => $s->id,
                'name' => $s->first_name . ' ' . $s->last_name,
                'belt' => $s->current_belt,
                'code' => $s->student_code
            ]);

        return response()->json($students);
    }

    public function bulkGenerate(Request $request)
    {
        $request->validate([
            'exam_id' => 'required|exists:belt_exams,id'
        ]);

        $exam = \App\Models\BeltExam::with(['results.student', 'results' => function($q) {
            $q->where('result', 'pass');
        }])->findOrFail($request->exam_id);

        $generated = [];

        foreach ($exam->results as $result) {
            $qrCode = 'TKD-' . strtoupper(Str::random(8));
            $verificationUrl = route('certificates.verify', $qrCode);

            $certificate = Certificate::create([
                'student_id' => $result->student_id,
                'certificate_type' => 'belt_promotion',
                'title' => 'Belt Promotion - ' . ucfirst($exam->belt_level),
                'description' => 'Successfully promoted to ' . ucfirst($exam->belt_level) . ' belt on ' . $exam->exam_date,
                'issued_date' => now(),
                'issued_by_user_id' => auth()->id(),
                'qr_code_value' => $qrCode,
                'verification_url' => $verificationUrl
            ]);

            $pdf = $this->generatePDF($certificate);
            $pdfPath = 'certificates/' . $qrCode . '.pdf';
            \Storage::disk('public')->put($pdfPath, $pdf->output());
            $certificate->update(['pdf_path' => $pdfPath]);

            $generated[] = $certificate;
        }

        return response()->json([
            'success' => true,
            'count' => count($generated),
            'certificates' => $generated
        ]);
    }

    private function generatePDF($certificate)
    {
        $data = [
            'certificate' => $certificate,
            'student' => $certificate->student,
            'qrCode' => base64_encode(QrCode::format('png')
                ->size(200)
                ->generate($certificate->verification_url))
        ];

        return PDF::loadView('certificates.template', $data);
    }
}
