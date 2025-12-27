<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestUsersSeeder extends Seeder
{
    public function run(): void
    {
        // Use firstOrCreate to avoid duplicates (checks by user_id)
        User::firstOrCreate(
            ['user_id' => 'admin001'],  // Unique field to check
            [
                'user_type_id' => 1,
                'bisu_email' => 'admin@example.com',
                'firstname' => 'System',
                'lastname' => 'Admin',
                'validation' => 'validated',
                'status' => 'active',
                'password' => Hash::make('admin1234'),
            ]
        );

        User::firstOrCreate(
            ['user_id' => 'staff001'],
            [
                'user_type_id' => 2,
                'bisu_email' => 'staff@example.com',
                'firstname' => 'Scholarship',
                'lastname' => 'Staff',
                'validation' => 'validated',
                'status' => 'active',
                'password' => Hash::make('staff1234'),
            ]
        );

        User::firstOrCreate(
            ['user_id' => 'student001'],
            [
                'user_type_id' => 3,
                'bisu_email' => 'student@example.com',
                'firstname' => 'Juan',
                'lastname' => 'Dela Cruz',
                'validation' => 'validated',
                'status' => 'active',
                'password' => Hash::make('student1234'),
            ]
        );
    }
}