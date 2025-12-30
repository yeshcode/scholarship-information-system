<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\UserType;
use Spatie\Permission\Models\Role;

class SyncUserTypesToRoles extends Seeder
{
    public function run(): void
    {
        $userTypes = UserType::all();
        foreach ($userTypes as $userType) {
            Role::firstOrCreate(['name' => $userType->name]);
        }
    }
}