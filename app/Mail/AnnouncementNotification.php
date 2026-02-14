<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AnnouncementNotification extends Mailable 
{
    use Queueable, SerializesModels;

    public $announcement;
    public $coordinatorEmail;

    public function __construct($announcement, $coordinatorEmail)
    {
        $this->announcement = $announcement;
        $this->coordinatorEmail = $coordinatorEmail;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Scholarship Announcement: ' . ($this->announcement['title'] ?? ''),
            // Use SYSTEM sender (from .env)
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            // Replies go to coordinator
            replyTo: [
                new Address($this->coordinatorEmail, 'Scholarship Coordinator')
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.announcement'
        );
    }
}
