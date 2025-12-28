<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserType;  // Ensure this model exists
use Spatie\Permission\Models\Role;

class UserTypeSeeder extends Seeder
{
    public function run(): void
{

    UserType::firstOrCreate(
        ['name' => 'Super Admin'],  // Unique check: Look for this name
        ['description' => 'Administrator with full access']  // Data to add if creating
    );

    // Step 2: Create 'Scholarship Coordinator' type if it doesn't exist
    UserType::firstOrCreate(
        ['name' => 'Scholarship Coordinator'],
        ['description' => 'Manages scholarships']
    );

    UserType::firstOrCreate(
        ['name' => 'Student'],
        ['description' => 'Student user']
    );

}
}