<?php

namespace App\Jobs;

use App\Models\Announcement;
use App\Models\Scholar;
use App\Models\User;
use App\Models\Notification;
use App\Mail\AnnouncementNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendAnnouncementNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $announcementId, public string $audience, public array $selectedScholarIds = [])
    {}

    public function handle(): void
    {
        $announcement = Announcement::find($this->announcementId);
        if (!$announcement) return;

        if ($this->audience === 'all_students') {
            $recipients = User::whereHas('userType', fn($q) => $q->where('name', 'Student'))->get();
        } else {
            $recipients = Scholar::whereIn('id', $this->selectedScholarIds)
                ->with('user')
                ->get()
                ->pluck('user');
        }

        $fromAddress = config('mail.from.address');
        $fromName = config('mail.from.name');

        foreach ($recipients as $user) {
            if (!$user?->bisu_email) continue;

            // Send email
            Mail::to($user->bisu_email)->send(
                (new AnnouncementNotification($announcement->toArray(), $fromAddress))
                    ->from($fromAddress, $fromName)
            );

            // Save notification in DB
            Notification::create([
                'recipient_user_id' => $user->id,
                'created_by' => $announcement->created_by,
                'type' => 'announcement',
                'title' => $announcement->title,
                'message' => $announcement->description,
                'related_type' => Announcement::class,
                'related_id' => $announcement->id,
                'is_read' => false,
                'sent_at' => now(),
            ]);
        }
    }
}
