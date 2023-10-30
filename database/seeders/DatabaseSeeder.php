<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            UserSeeder::class,
            PermissionRoleSeeder::class,
            RoleUserSeeder::class,
        ]);

        Setting::create([
            "parameter" => "BOOKING_INTERVAL",
            "value" => "30"
        ]);
    }
}
