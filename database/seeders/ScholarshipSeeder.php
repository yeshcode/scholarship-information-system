<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Scholarship;
use App\Models\User;

class ScholarshipSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('user_type_id', 2)->get(); // Assuming 2 is Scholarship Coordinator
        if ($users->isEmpty()) {
            $users = User::factory()->count(2)->create(['user_type_id' => 2]); // Create if none
        }

        Scholarship::create([
            'scholarship_name' => 'Merit Scholarship',
            'description' => 'For high-achieving students with GPA 3.5+',
            'requirements' => 'GPA 3.5, Full-time enrollment',
            'benefactor' => 'University Foundation',
            'status' => 'open',
            'created_by' => $users->random()->id,
            'updated_by' => $users->random()->id,
        ]);

        Scholarship::create([
            'scholarship_name' => 'Need-Based Scholarship',
            'description' => 'For students with financial need',
            'requirements' => 'Income below threshold, Application form',
            'benefactor' => 'Alumni Association',
            'status' => 'open',
            'created_by' => $users->random()->id,
            'updated_by' => $users->random()->id,
        ]);

        // Add more as needed
    }
}