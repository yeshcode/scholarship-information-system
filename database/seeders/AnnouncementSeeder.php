<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Announcement;
use App\Models\Notification;
use App\Models\User;

class AnnouncementSeeder extends Seeder {
    public function run(): void {
        // Create a test announcement
        $announcement = Announcement::create([
            'created_by' => 1, // Coordinator user ID
            'title' => 'Test Announcement',
            'description' => 'This is a test for all students.',
            'audience' => 'all_students',
            'posted_at' => now(),
        ]);

        // Create notifications for all students
        $students = User::whereHas('userType', fn($q) => $q->where('name', 'Student'))->get();
        foreach ($students as $student) {
            Notification::create([
                'recipient_user_id' => $student->id,
                'created_by' => 1,
                'type' => 'announcement',
                'title' => $announcement->title,
                'message' => substr($announcement->description, 0, 100) . '...', // Glimpse
                'related_type' => Announcement::class,
                'related_id' => $announcement->id,
                'is_read' => false,
                'sent_at' => now(),
            ]);
        }
    }
}