<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserType;  // Ensure you have this model

class UserTypesSeeder extends Seeder
{
    public function run(): void
    {
        UserType::firstOrCreate(['name' => 'Super Admin']);
        UserType::firstOrCreate(['name' => 'Scholarship Coordinator']);
        UserType::firstOrCreate(['name' => 'Student']);
    }
}