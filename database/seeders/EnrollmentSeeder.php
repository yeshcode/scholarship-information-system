<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Enrollment;

class EnrollmentSeeder extends Seeder
{
    public function run(): void
    {
        Enrollment::firstOrCreate([
            'user_id' => 1,       // Assumes User ID 1 exists
            'semester_id' => 1,   // Assumes Semester ID 1 exists
            'section_id' => 1,    // Assumes Section ID 1 exists
            'status' => 'active',
        ]);
    }
}