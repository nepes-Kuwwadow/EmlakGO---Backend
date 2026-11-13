<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Ulanyjylary döretmek
     */
    public function run(): void
    {
        // Admin ulanyjy döretmek
        User::firstOrCreate(
            ['email' => 'admin@emlakgo.tm'],
            [
                'name'     => 'Admin',
                'email'    => 'admin@emlakgo.tm',
                'password' => Hash::make('admin123456'),
                'role'     => 'admin',
                'phone'    => '+993 12 000000',
            ]
        );

        // Adaty ulanyjylar döretmek
        $users = [
            ['name' => 'Myrat Annagurbanow',  'email' => 'myrat@test.tm',  'phone' => '+993 65 100001'],
            ['name' => 'Aýnur Mämmедowa',      'email' => 'aynur@test.tm',  'phone' => '+993 65 100002'],
            ['name' => 'Döwlet Hojageldiyew',  'email' => 'dowlet@test.tm', 'phone' => '+993 65 100003'],
            ['name' => 'Leýla Gurbandurdyýewa','email' => 'leyla@test.tm',  'phone' => '+993 65 100004'],
            ['name' => 'Serdar Ataýew',        'email' => 'serdar@test.tm', 'phone' => '+993 65 100005'],
        ];

        foreach ($users as $userData) {
            User::firstOrCreate(
                ['email' => $userData['email']],
                array_merge($userData, [
                    'password' => Hash::make('password123'),
                    'role'     => 'user',
                ])
            );
        }
    }
}
