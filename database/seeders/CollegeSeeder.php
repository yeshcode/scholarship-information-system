<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\College;

class CollegeSeeder extends Seeder
{
    public function run(): void
    {
        College::firstOrCreate(['college_name' => 'College of Engineering']);
        College::firstOrCreate(['college_name' => 'College of Arts and Sciences']);
    }
}