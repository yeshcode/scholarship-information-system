<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AnnouncementNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $announcement;
    public $coordinatorEmail;

    public function __construct($announcement, $coordinatorEmail)  // Ensure both parameters are here
    {
        $this->announcement = $announcement;
        $this->coordinatorEmail = $coordinatorEmail;  // Assign the variable
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Scholarship Announcement: ' . $this->announcement['title'],
            from: $this->coordinatorEmail,  // Use the dynamic email
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.announcement');
    }
}