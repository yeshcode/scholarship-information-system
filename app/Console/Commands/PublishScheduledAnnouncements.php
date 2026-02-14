<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Announcement;
use App\Jobs\SendAnnouncementNotifications;

class PublishScheduledAnnouncements extends Command
{
    protected $signature = 'announcements:publish-scheduled';
    protected $description = 'Send notifications for announcements whose posted_at has arrived';

    public function handle(): int
    {
        $due = Announcement::query()
            ->whereNotNull('posted_at')
            ->where('posted_at', '<=', now())
            ->whereNull('notified_at')
            ->orderBy('posted_at')
            ->limit(50)
            ->get();

        foreach ($due as $a) {

            if (in_array($a->audience, ['specific_students', 'specific_scholars'])) {

                $userIds = $a->recipients()->pluck('users.id')->toArray();

                SendAnnouncementNotifications::dispatch(
                    $a->id,
                    $a->created_by,
                    'specific_students',
                    $userIds,
                    []
                );

            } else {

                SendAnnouncementNotifications::dispatch(
                    $a->id,
                    $a->created_by,
                    $a->audience,
                    [],
                    []
                );
            }

            $a->update(['notified_at' => now()]);
        }

        $this->info("Published: " . $due->count());
        return self::SUCCESS;
    }
}
