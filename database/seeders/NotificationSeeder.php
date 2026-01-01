<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;
use App\Models\Announcement;
use App\Models\Stipend;
use App\Models\Scholar;
use Carbon\Carbon;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $students = User::where('user_type_id', 3)->get(); // Recipients (students/scholars)
        $coordinators = User::where('user_type_id', 2)->get(); // Creators
        $announcements = Announcement::all();
        $stipends = Stipend::all();
        $scholars = Scholar::all();

        if ($students->isEmpty() || $coordinators->isEmpty() || $announcements->isEmpty() || $stipends->isEmpty() || $scholars->isEmpty()) {
            $this->command->info('Run UserSeeder, AnnouncementSeeder, StipendSeeder, and ScholarSeeder first to ensure data exists.');
            return;
        }

        // Define allowed related types
        $relatedTypes = ['announcement', 'stipend', 'scholarship'];

        // Create sample notifications with variety
        foreach ($relatedTypes as $type) {
            $relatedId = null;
            switch ($type) {
                case 'announcement':
                    $relatedId = $announcements->random()->id;
                    break;
                case 'stipend':
                    $relatedId = $stipends->random()->id;
                    break;
                case 'scholarship':
                    $relatedId = $scholars->random()->id;
                    break;
            }

            Notification::create([
                'recipient_user_id' => $students->random()->id,  // References users.id (bigint)
                'created_by' => $coordinators->random()->id,  // References users.id (bigint)
                'type' => $type,  // e.g., 'announcement'
                'title' => ucfirst($type) . ' Notification',
                'message' => 'Check the latest ' . $type . ' update.',
                'related_type' => $type,  // Valid string value
                'related_id' => $relatedId,  // References the appropriate table's ID
                'is_read' => false,
                'date' => Carbon::now(),
            ]);
        }

        // Add more random notifications
        for ($i = 0; $i < 5; $i++) {
            $type = collect($relatedTypes)->random();
            $relatedId = null;
            switch ($type) {
                case 'announcement':
                    $relatedId = $announcements->random()->id;
                    break;
                case 'stipend':
                    $relatedId = $stipends->random()->id;
                    break;
                case 'scholarship':
                    $relatedId = $scholars->random()->id;
                    break;
            }

            Notification::create([
                'recipient_user_id' => $students->random()->id,
                'created_by' => $coordinators->random()->id,
                'type' => $type,
                'title' => 'Notification ' . ($i + 4),
                'message' => 'This is a sample ' . $type . ' notification.',
                'related_type' => $type,
                'related_id' => $relatedId,
                'is_read' => false,
                'date' => Carbon::now(),
            ]);
        }

        $this->command->info('Notifications seeded successfully.');
    }
}