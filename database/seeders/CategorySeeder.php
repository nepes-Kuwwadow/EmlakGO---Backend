<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Kategoriýalary döretmek
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Kwartira',      'slug' => 'kwartira'],
            ['name' => 'Ýer',           'slug' => 'yer'],
            ['name' => 'Ofis',          'slug' => 'ofis'],
            ['name' => 'Jaý',           'slug' => 'jay'],
            ['name' => 'Kireýine Jaý',  'slug' => 'kireyine-jay'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
