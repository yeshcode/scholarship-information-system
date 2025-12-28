<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;                    // Import User model
use Illuminate\Support\Facades\Hash;   // For hashing passwords

class TestUsersSeeder extends Seeder
{
    public function run(): void
    {
        // --- Create Super Admin User ---
        // firstOrCreate: Checks for existing user by user_id and bisu_email; creates if not found
        $admin = User::firstOrCreate([
            'user_id' => 'admin001',           // Unique ID
            'bisu_email' => 'admin@example.com', // Login email
        ], [
            'user_type_id' => 1,               // Links to 'Super Admin' in user_types
            'firstname' => 'System',
            'lastname' => 'Admin',
            'status' => 'active',
            'contact_no' => '09062708621',
            'password' => Hash::make('adminpass'), // Hashed for security
        ]);
        // Debug: Print to terminal to confirm creation
        echo "Created/Found User: " . $admin->firstname . " " . $admin->lastname . " (ID: " . $admin->id . ")\n";
        // Assign Spatie role
        $admin->assignRole('Super Admin');
        echo "Assigned Role: Super Admin to User ID " . $admin->id . "\n";

        // --- Create Scholarship Coordinator User ---
        $coord = User::firstOrCreate([
            'user_id' => 'coord001',
            'bisu_email' => 'coord@example.com',
        ], [
            'user_type_id' => 2,               // Links to 'Scholarship Coordinator'
            'firstname' => 'Scholarship',
            'lastname' => 'Coordinator',
            'status' => 'active',
            'contact_no' => '09062708621',
            'password' => Hash::make('coordpass'),
        ]);
        echo "Created/Found User: " . $coord->firstname . " " . $coord->lastname . " (ID: " . $coord->id . ")\n";
        $coord->assignRole('Scholarship Coordinator');
        echo "Assigned Role: Scholarship Coordinator to User ID " . $coord->id . "\n";

        // --- Create Student User ---
        $student = User::firstOrCreate([
            'user_id' => 'stud001',
            'bisu_email' => 'student@example.com',
        ], [
            'user_type_id' => 3,               // Links to 'Student'
            'firstname' => 'John',
            'lastname' => 'Doe',
            'student_id' => '12345',           // For students (used as password in login)
            'status' => 'active',
            'contact_no' => '09062708621',
            'password' => Hash::make('12345'), // Hashed (login checks plain student_id for students)
        ]);
        echo "Created/Found User: " . $student->firstname . " " . $student->lastname . " (ID: " . $student->id . ")\n";
        $student->assignRole('Student');
        echo "Assigned Role: Student to User ID " . $student->id . "\n";
    }
}