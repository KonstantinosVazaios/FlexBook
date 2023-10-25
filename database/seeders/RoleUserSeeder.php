<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleUserSeeder extends Seeder
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
        
        User::find(1)->roles()->attach(1);
    }
}
