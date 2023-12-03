<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get data from the JSON file
        $jsonFilePath = database_path('seeders/json/clients.json');
        $clients = json_decode(file_get_contents($jsonFilePath), true);

        $formattedData = [];

        foreach ($clients as $client) {
            $firstname = strlen($client['firstname'] ?? '') > 2 ? $client['firstname'] : null;
            $lastname = $client['lastname'];

            $mobile = $client['mobile'];
            $email = strlen($client['email'] ?? '') > 4 ? $client['email'] : null;

            $formattedData[] = [
                "name" => trim("{$firstname} {$lastname}"),
                "email" => $email,
                "telephone" => $mobile
            ];
        }

        // Use array_reduce to filter based on unique emails
        $uniqueData = array_reduce($formattedData, function ($carry, $item) {
            // Use the email as the key to check for uniqueness
            $email = $item['email'];
            if (!isset($carry[$email])) {
                $carry[$email] = $item;
            }
            return $carry;
        }, []);

        // Reset array keys to have consecutive numeric keys
        $uniqueData = array_values($uniqueData);

        DB::table('users')->insert($uniqueData);

        $idsCount = count($uniqueData);
        $associations = [];

        for ($i=1; $i <= $idsCount; $i++) {
            $associations[] = [
                "role_id" => 4,
                "user_id" => $i
            ];
        }

        DB::table('role_user')->insert($associations);
    }
}
