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

        // Step 3: Seed foundational tables (colleges, year_levels, semesters)
        // These are independent and needed for other tables.
        $this->call(CollegeSeeder::class);      // Seeds colleges
        $this->call(YearLevelSeeder::class);    // Seeds year_levels
        $this->call(SemesterSeeder::class);     // Seeds semesters

        // Step 4: Seed courses (depends on colleges)
        $this->call(CourseSeeder::class);       // Seeds courses

        // Step 5: Seed sections (depends on courses and year_levels)
        $this->call(SectionSeeder::class);      // Seeds sections

        // Step 6: Seed users LAST
        // Now that user_types, roles, colleges, etc., exist, create users with links.
        $this->call(TestUsersSeeder::class);

        // Step 7: Seed enrollments LAST (depends on users, semesters, sections)
        $this->call(EnrollmentSeeder::class);   // Seeds enrollments
    }
}