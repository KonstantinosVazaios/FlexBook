<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Service;
use App\Models\ServiceGroup;
use App\Models\Setting;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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

        // Get data from the JSON file
        $jsonFilePath = database_path('seeders/json/ferentinos_setup.json');
        $jsonData = json_decode(file_get_contents($jsonFilePath), true);

        // Seed Store
        $store = Store::create([
            "name" => $jsonData["store"]["name"],
            "description" => $jsonData["store"]["description"],
            "address" => $jsonData["store"]["address"],
            "telephone" => $jsonData["store"]["telephone"],
            "email" => $jsonData["store"]["email"],
            "active" => $jsonData["store"]["active"],
        ]);

        foreach ($jsonData['store']['store_hours'] as $storeHourData) {
            $store->storeHours()->create([
                "day" => $storeHourData["day"],
                "is_open" => $storeHourData["is_open"],
                "open" => $storeHourData["open"],
                "close" => $storeHourData["close"]
            ]);
        }

        // Seed Users
        foreach ($jsonData['users'] as $userData) {
            $user = User::create([
                "name" => $userData["name"],
                "telephone" => $userData["telephone"],
                "email" => $userData["email"],
                "password" => Hash::make($userData["password"]),
            ]);

            foreach ($userData['roles'] as $role) {
                if ($role === 'admin') {
                    $user->roles()->attach(2);
                }

                if ($role === 'staff') {
                    $user->roles()->attach(3);
                }

                if ($role === 'client') {
                    $user->roles()->attach(4);
                }
            }

            foreach ($userData['work_hours'] as $workHourData) {
                $user->workHours()->create([
                    "store_id" => $workHourData["store_id"],
                    "day" => $workHourData["day"],
                    "off_work" => $workHourData["off_work"],
                    "start" => $workHourData["start"],
                    "end" => $workHourData["end"]
                ]);
            }
        }

        // Seed Service Groups
        foreach ($jsonData['service_groups'] as $serviceGroupData) {
            ServiceGroup::create([
                "name" => $serviceGroupData["name"],
                "description" => $serviceGroupData["description"],
            ]);
        }

        // Seed Services
        foreach ($jsonData['services'] as $serviceData) {
            $serviceGroupsIds = $serviceData['service_groups_ids'];

            $service = Service::create([
                "name" => $serviceData["name"],
                "description" => $serviceData["description"],
                "default_price" => $serviceData["default_price"],
                "default_duration" => $serviceData["default_duration"],
                "active" => $serviceData["active"],
            ]);

            $store->services()->attach($service->id);

            foreach ($serviceGroupsIds as $groupId) {
                $service->serviceGroups()->attach($groupId);
            }
        }

        $services = Service::pluck('id');
        User::whereHas('roles', function (Builder $query) {
            $query->where('code', 'staff');
        })
        ->get()
        ->map(function ($user) use ($services) {
            $user->services()->attach($services);
        });


    }
}
