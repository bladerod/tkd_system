<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AnnouncementEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $title;
    public $messageBody;

    public function __construct($title, $messageBody)
    {
        $this->title = $title;
        $this->messageBody = $messageBody;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Announcement: ' . $this->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            // We will create this blade view in the next step
            view: 'emails.announcement', 
        );
    }
}
