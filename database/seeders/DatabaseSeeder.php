<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * EmläkGO platformasynyň başlangyç maglumatlary
     */
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            UserSeeder::class,
            PropertySeeder::class,
            CommentSeeder::class,
        ]);
    }
}
