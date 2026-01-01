<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ScholarshipBatch;
use App\Models\Scholarship;
use App\Models\Semester;

class ScholarshipBatchSeeder extends Seeder
{
    public function run(): void
    {
        $scholarships = Scholarship::all();
        $semesters = Semester::all();
        if ($scholarships->isEmpty() || $semesters->isEmpty()) {
            $this->command->info('Run ScholarshipSeeder and Semester seeder first.');
            return;
        }

        ScholarshipBatch::create([
            'scholarship_id' => $scholarships->random()->id,
            'semester_id' => $semesters->random()->id,
            'batch_number' => 'Batch 1 - Fall 2023',
        ]);

        ScholarshipBatch::create([
            'scholarship_id' => $scholarships->random()->id,
            'semester_id' => $semesters->random()->id,
            'batch_number' => 'Batch 2 - Spring 2024',
        ]);
    }
}