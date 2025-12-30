<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserType;

class UpdateUserTypesWithDashboardUrls extends Seeder
{
    public function run(): void
    {
        // Update existing user types with dashboard URLs
        UserType::where('name', 'Super Admin')->update(['dashboard_url' => '/admin/dashboard']);
        UserType::where('name', 'Scholarship Coordinator')->update(['dashboard_url' => '/coordinator/dashboard']);
        UserType::where('name', 'Student')->update(['dashboard_url' => '/student/dashboard']);
        
        // Add for new roles as needed, e.g., UserType::where('name', 'Guest')->update(['dashboard_url' => '/guest/dashboard']);
    }
}