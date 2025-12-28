<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Semester;

class SemesterSeeder extends Seeder
{
    public function run(): void
    {
        Semester::firstOrCreate([
            'term' => '1st Semester',
            'academic_year' => '2023-2024',
            'start_date' => '2023-08-01',
            'end_date' => '2023-12-31',
            'is_current' => true,
        ]);
    }
}