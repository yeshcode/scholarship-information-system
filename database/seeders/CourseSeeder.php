<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Course;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        Course::firstOrCreate([
            'course_name' => 'Computer Science',
            'course_description' => 'Intro to CS',
            'college_id' => 1,  // Assumes College ID 1 exists
        ]);
    }
}