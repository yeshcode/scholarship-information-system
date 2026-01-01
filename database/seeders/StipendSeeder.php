<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Stipend;
use App\Models\Scholar;
use App\Models\User;
use App\Models\StipendsRelease;  // Matches your model file name

class StipendSeeder extends Seeder
{
    public function run(): void
    {
        $scholars = Scholar::all();
        $students = User::where('user_type_id', 3)->get(); // Students
        $stipendReleases = StipendsRelease::all();  // Corrected model name
        $coordinators = User::where('user_type_id', 2)->get(); // Coordinators

        if ($scholars->isEmpty() || $students->isEmpty() || $stipendReleases->isEmpty() || $coordinators->isEmpty()) {
            $this->command->info('Run ScholarSeeder, UserSeeder, and StipendsReleaseSeeder first. Ensure all data exists.');
            return;
        }

        // Define allowed statuses from your enum in the migration
        $statuses = ['for_release', 'released', 'returned', 'waiting'];

        // Create sample stipends
        Stipend::create([
            'stipend_release_id' => $stipendReleases->random()->id,
            'scholar_id' => $scholars->random()->id,  // Now singular to match model/migration
            'student_id' => $students->random()->id,  // References users.id (bigint)
            'created_by' => $coordinators->random()->id,  // References users.id (bigint)
            'updated_by' => $coordinators->random()->id,  // References users.id (bigint)
            'amount_received' => 500,
            'status' => 'released',  // Valid status
        ]);

        Stipend::create([
            'stipend_release_id' => $stipendReleases->random()->id,
            'scholar_id' => $scholars->random()->id,
            'student_id' => $students->random()->id,
            'created_by' => $coordinators->random()->id,
            'updated_by' => $coordinators->random()->id,
            'amount_received' => 450,
            'status' => 'for_release',  // Valid status
        ]);

        // Add more with random valid statuses
        for ($i = 0; $i < 3; $i++) {
            Stipend::create([
                'stipend_release_id' => $stipendReleases->random()->id,
                'scholar_id' => $scholars->random()->id,
                'student_id' => $students->random()->id,
                'created_by' => $coordinators->random()->id,
                'updated_by' => $coordinators->random()->id,
                'amount_received' => rand(400, 600),
                'status' => collect($statuses)->random(),  // Random valid status
            ]);
        }

        $this->command->info('Stipends seeded successfully.');
    }
}