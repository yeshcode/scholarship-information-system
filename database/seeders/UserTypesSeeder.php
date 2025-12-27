<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UserType;  // Assuming you have a UserType model

class UserTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Use firstOrCreate to avoid duplicates
        UserType::firstOrCreate(['type' => 'admin'], ['description' => 'System Administrator']);
        UserType::firstOrCreate(['type' => 'staff'], ['description' => 'Scholarship Coordinator']);
        UserType::firstOrCreate(['type' => 'student'], ['description' => 'BISU Enrolled Student']);
        UserType::firstOrCreate(['type' => 'guest'], ['description' => 'Visitor Access']);
    }
}