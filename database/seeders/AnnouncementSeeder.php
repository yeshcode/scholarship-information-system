<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Announcement;  // Assuming you have an Announcement model
use App\Models\User;
use Carbon\Carbon;  // For timestamps

class AnnouncementSeeder extends Seeder
{
    public function run(): void
    {
        $coordinators = User::where('user_type_id', 2)->get(); // Coordinators

        if ($coordinators->isEmpty()) {
            $this->command->info('Run UserSeeder first to ensure coordinators exist.');
            return;
        }

        // Create sample announcements
        Announcement::create([
            'created_by' => $coordinators->random()->id,  // References users.id (bigint)
            'title' => 'New Scholarship Application Open',
            'description' => 'Applications for Merit Scholarship are now open.',
            'posted_on' => Carbon::now(),  // Set to current timestamp to avoid null
        ]);

        Announcement::create([
            'created_by' => $coordinators->random()->id,
            'title' => 'Scholarship Deadline Extended',
            'description' => 'The deadline for Academic Excellence Scholarship has been extended to next month.',
            'posted_on' => Carbon::now(),
        ]);

        // Add more if needed
        for ($i = 0; $i < 3; $i++) {
            Announcement::create([
                'created_by' => $coordinators->random()->id,
                'title' => 'Announcement ' . ($i + 3),
                'description' => 'This is a sample announcement description.',
                'posted_on' => Carbon::now(),
            ]);
        }

        $this->command->info('Announcements seeded successfully.');
    }
}