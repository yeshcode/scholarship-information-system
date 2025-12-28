<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;  // Import Spatie's Role model for seeding roles

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Step 1: Seed user_types FIRST
        // This populates the user_types table (needed for users' foreign key).
        $this->call(UserTypeSeeder::class);

        // Step 2: Seed Spatie roles
        // These are for permissions (e.g., 'Super Admin' can access admin features).
        // firstOrCreate prevents duplicates if you run seeding multiple times.
        Role::firstOrCreate(['name' => 'Super Admin']);
        Role::firstOrCreate(['name' => 'Scholarship Coordinator']);
        Role::firstOrCreate(['name' => 'Student']);

        // Step 3: Seed users LAST
        // Now that user_types and roles exist, create users with links to both.
        $this->call(TestUsersSeeder::class);
    }
}