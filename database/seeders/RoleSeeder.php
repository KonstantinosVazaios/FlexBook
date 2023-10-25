<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Role::create([
            'code' => 'developer',
            'name' => 'Developer',
            'description' => 'Handles application configuration things'
        ]);

        Role::create([
            'code' => 'admin',
            'name' => 'Admin',
            'description' => 'Handles almost everything in the application'
        ]);

        Role::create([
            'code' => 'staff',
            'name' => 'Staff',
            'description' => 'Handles specific things on the BackOffice'
        ]);

        Role::create([
            'code' => 'client',
            'name' => 'Client',
            'description' => 'Only uses the end-user application'
        ]);
    }
}
