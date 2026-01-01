<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StipendsRelease;
use App\Models\User;
use App\Models\ScholarshipBatch;

class StipendReleaseSeeder extends Seeder
{
    public function run(): void
    {
        $batches = ScholarshipBatch::all();
        $coordinators = User::where('user_type_id', 2)->get(); // Coordinators/Staff

        if ($batches->isEmpty() || $coordinators->isEmpty()) {
            $this->command->info('Run ScholarshipBatchSeeder and UserSeeder first. Ensure coordinators exist.');
            return;
        }

        // Create sample stipend releases
        StipendsRelease::create([
            'batch_id' => $batches->random()->id,
            'created_by' => $coordinators->random()->id,  // Use users.id (bigint)
            'updated_by' => $coordinators->random()->id,  // Use users.id (bigint)
            'title' => 'Stipend Release for Batch 1',
            'amount' => 5000,
            'status' => 'pending',
            'date_release' => now()->addDays(7),
            'notes' => 'First release of the semester',
        ]);

        // Add more if needed
        StipendsRelease::create([
            'batch_id' => $batches->random()->id,
            'created_by' => $coordinators->random()->id,
            'updated_by' => $coordinators->random()->id,
            'title' => 'Stipend Release for Batch 2',
            'amount' => 4500,
            'status' => 'released',
            'date_release' => now()->addDays(14),
            'notes' => 'Second release of the semester',
        ]);

        $this->command->info('Stipend releases seeded successfully.');
    }
}