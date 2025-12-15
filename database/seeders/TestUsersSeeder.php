<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestUsersSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'user_type_id' => 1,
            'user_id' => 'admin001',
            'bisu_email' => 'admin@example.com',
            'firstname' => 'System',
            'lastname' => 'Admin',
            'validation' => 'validated',
            'status' => 'active',
            'password' => Hash::make('admin1234'),
        ]);

        User::create([
            'user_type_id' => 2,
            'user_id' => 'staff001',
            'bisu_email' => 'staff@example.com',
            'firstname' => 'Scholarship',
            'lastname' => 'Staff',
            'validation' => 'validated',
            'status' => 'active',
            'password' => Hash::make('staff1234'),
        ]);

        User::create([
            'user_type_id' => 3,
            'user_id' => 'student001',
            'bisu_email' => 'student@example.com',
            'firstname' => 'Juan',
            'lastname' => 'Dela Cruz',
            'validation' => 'validated',
            'status' => 'active',
            'password' => Hash::make('student1234'),
        ]);
    }
}
