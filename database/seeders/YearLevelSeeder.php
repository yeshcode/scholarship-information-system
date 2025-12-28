<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\YearLevel;

class YearLevelSeeder extends Seeder
{
    public function run(): void
    {
        YearLevel::firstOrCreate(['year_level_name' => '1st Year']);
        YearLevel::firstOrCreate(['year_level_name' => '2nd Year']);
    }
}