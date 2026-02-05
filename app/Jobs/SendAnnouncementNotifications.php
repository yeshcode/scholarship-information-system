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
use Illuminate\Support\Facades\Log;

class SendAnnouncementNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $announcementId,
        public int $creatorId,
        public string $audience,
        public array $selectedUserIds = [],     // for specific_students
        public array $selectedScholarIds = []   // for specific_scholars
    ) {}

    public function handle(): void
    {
        $announcement = Announcement::find($this->announcementId);
        if (!$announcement) return;

        $creator = User::find($this->creatorId);
        $replyTo = $creator?->bisu_email ?: config('mail.from.address');

        // ✅ Use chunking so it doesn't load thousands of users in memory
        if ($this->audience === 'all_students') {

            User::whereHas('userType', fn($q) => $q->where('name', 'Student'))
                ->select('id', 'bisu_email')
                ->chunkById(500, function ($users) use ($announcement, $replyTo) {
                    $this->processUsers($users, $announcement, $replyTo);
                });

            return;
        }

        if ($this->audience === 'all_scholars') {

            Scholar::with('user:id,bisu_email')
                ->select('id','student_id')
                ->chunkById(500, function ($scholars) use ($announcement, $replyTo) {
                    $users = $scholars->pluck('user')->filter();
                    $this->processUsers($users, $announcement, $replyTo);
                });

            return;
        }

        if ($this->audience === 'specific_students') {

            $users = User::whereIn('id', $this->selectedUserIds)
                ->get(['id','bisu_email']);

            $this->processUsers($users, $announcement, $replyTo);
            return;
        }

        if ($this->audience === 'specific_scholars') {

            $scholars = Scholar::whereIn('id', $this->selectedScholarIds)
                ->with('user:id,bisu_email')
                ->get();

            $users = $scholars->pluck('user')->filter();
            $this->processUsers($users, $announcement, $replyTo);
            return;
        }
    }

    private function processUsers($users, Announcement $announcement, string $replyToEmail): void
    {
        foreach ($users as $user) {
            if (!$user) continue;

            // ✅ Always create system notification (even if no email)
            Notification::create([
                'recipient_user_id' => $user->id,
                'created_by' => $this->creatorId,
                'type' => 'announcement',
                'title' => $announcement->title,
                'message' => $announcement->description,
                'related_type' => Announcement::class,
                'related_id' => $announcement->id,
                'is_read' => false,
                'sent_at' => now(),
            ]);

            // ✅ Email only if valid
           $email = trim((string)($user->bisu_email ?? ''));

           // ❌ skip if empty or invalid format
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                continue;
            }

            // ❌ skip if NOT a real BISU email
            if (!str_ends_with(strtolower($email), '@bisu.edu.ph')) {
                continue;
            }

           try {
                // ✅ IMPORTANT: use queue(), not send()
                Mail::to($email)->queue(
                    new AnnouncementNotification($announcement->toArray(), $replyToEmail)
                );
            } catch (\Throwable $e) {
                Log::error("Announcement email failed", [
                    'user_id' => $user->id,
                    'email' => $email,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}
