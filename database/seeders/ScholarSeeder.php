<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Scholar;
use App\Models\User;
use App\Models\ScholarshipBatch;

class ScholarSeeder extends Seeder
{
    public function run(): void
    {
        $students = User::where('user_type_id', 3)->get(); // Students
        $batches = ScholarshipBatch::all();
        $coordinators = User::where('user_type_id', 2)->get(); // Coordinators

        if ($students->isEmpty() || $batches->isEmpty() || $coordinators->isEmpty()) {
            $this->command->info('Run UserSeeder and ScholarshipBatchSeeder first. Ensure coordinators exist.');
            return;
        }

        // Create 5 scholars (adjust as needed)
        for ($i = 0; $i < 5; $i++) {
            Scholar::create([
                'student_id' => $students->random()->id,  // References users.id (bigint PK)
                'batch_id' => $batches->random()->id,
                'updated_by' => $coordinators->random()->id,  // References users.id (bigint PK)
                'date_added' => now(),
                'status' => 'active',
            ]);
        }

        $this->command->info('5 scholars seeded successfully.');
    }
}