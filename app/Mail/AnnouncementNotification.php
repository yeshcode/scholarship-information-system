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

    public function __construct($announcement)
    {
        $this->announcement = $announcement;
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'New Scholarship Announcement: ' . $this->announcement['title']);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.announcement');
    }
}