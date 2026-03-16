<?php
// app/Mail/CertificateMail.php
namespace App\Mail;

use App\Models\Certificate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CertificateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $certificate;

    public function __construct(Certificate $certificate)
    {
        $this->certificate = $certificate;
    }

    public function build()
    {
        $pdfPath = storage_path('app/public/' . $this->certificate->pdf_path);

        return $this->subject('Your Taekwondo Certificate - ' . $this->certificate->title)
            ->view('emails.certificate')
            ->attach($pdfPath, [
                'as' => $this->certificate->qr_code_value . '.pdf',
                'mime' => 'application/pdf',
            ]);
    }
}
