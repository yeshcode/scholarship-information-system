<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Section;

class SectionSeeder extends Seeder
{
    public function run(): void
    {
        Section::firstOrCreate([
            'section_name' => 'Section A',
            'course_id' => 1,      // Assumes Course ID 1 exists
            'year_level_id' => 1,  // Assumes YearLevel ID 1 exists
        ]);
    }
}