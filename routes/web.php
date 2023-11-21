<?php

use App\Models\Service;
use App\Models\ServiceGroup;
use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::view('/', 'welcome');

Route::get('test', function () {
    // Get data from the JSON file
    $jsonFilePath = database_path('seeders/json/ferentinos_setup.json');
    $jsonData = json_decode(file_get_contents($jsonFilePath), true);

    // Seed Store
    $store = Store::create($jsonData['store']);
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
        $user = User::create($userData);

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
        ServiceGroup::create($serviceGroupData);
    }

    // Seed Services
    foreach ($jsonData['services'] as $serviceData) {
        $serviceGroupsIds = $serviceData['service_groups_ids'];

        $service = Service::create($serviceData);

        foreach ($serviceGroupsIds as $groupId) {
            $service->serviceGroups()->attach($groupId);
        }
    }

});

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
