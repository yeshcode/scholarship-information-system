<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call your custom seeders here
        $this->call([
            UserTypesSeeder::class,
            TestUsersSeeder::class,
        ]);


        Role::create(['name' => 'Super Admin']);
        Role::create(['name' => 'Scholarship Coordinator']);
        Role::create(['name' => 'Student']);
        Role::create(['name' => 'Guest']);

        // OPTIONAL: Create a test admin (you can remove if not needed)
        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
