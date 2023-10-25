<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1: DEVELOPER
        // 2: ADMIN
        // 3: STAFF
        // 4: CLIENT

        $developer = Role::where('code', 'developer')->first();
        $admin = Role::where('code', 'admin')->first();
        $staff = Role::where('code', 'staff')->first();
        $client = Role::where('code', 'client')->first();

        $developer->permissions()->attach([1]);
    }
}
