<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class UserTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('user_types')->insert([
            ['type' => 'admin', 'description' => 'System Administrator'],
            ['type' => 'staff', 'description' => 'Scholarship Coordinator'],
            ['type' => 'student', 'description' => 'BISU Enrolled Student'],
            ['type' => 'guest', 'description' => 'Visitor Access'],
        ]);
    }
}
