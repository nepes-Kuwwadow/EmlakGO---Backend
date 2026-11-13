<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Property;
use App\Models\PropertyImage;
use App\Models\User;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    /**
     * Emläkleri döretmek
     */
    public function run(): void
    {
        $users      = User::where('role', 'user')->get();
        $categories = Category::all();

        if ($users->isEmpty() || $categories->isEmpty()) {
            $this->command->warn('Ulanyjy ýa-da kategoriýa ýok. Önce UserSeeder we CategorySeeder-i işlediň.');
            return;
        }

        $cities = ['Aşgabat', 'Mary', 'Türkmenabat', 'Daşoguz', 'Balkanabat'];

        $properties = [
            ['title' => '3 otagly kwartira Çoganly ýaşaýyş toplumynda',  'city' => 'Aşgabat', 'price' => 85000, 'rooms' => 3, 'area' => 95.5,  'status' => 'active'],
            ['title' => '2 otagly kwartira Parahat 7-de',                  'city' => 'Aşgabat', 'price' => 55000, 'rooms' => 2, 'area' => 65.0,  'status' => 'active'],
            ['title' => 'Täze jaý Bagtyýarlyk etrapçasynda',               'city' => 'Aşgabat', 'price' => 250000,'rooms' => 5, 'area' => 320.0, 'status' => 'active'],
            ['title' => 'Ofis jaý merkezinde',                             'city' => 'Aşgabat', 'price' => 120000,'rooms' => null,'area' => 150.0,'status' => 'active'],
            ['title' => '10 sotly ýer Ruhabat etrabyynda',                 'city' => 'Aşgabat', 'price' => 45000, 'rooms' => null,'area' => 1000.0,'status' => 'active'],
            ['title' => 'Kireýine 2 otagly kwartira Mollanepes köçesinde', 'city' => 'Aşgabat', 'price' => 800,   'rooms' => 2, 'area' => 60.0,  'status' => 'active'],
            ['title' => '4 otagly jaý Mary şäherinde',                    'city' => 'Mary',     'price' => 75000, 'rooms' => 4, 'area' => 180.0, 'status' => 'active'],
            ['title' => '1 otagly kwartira Türkmenabatda',                 'city' => 'Türkmenabat','price' => 25000,'rooms' => 1,'area' => 42.0, 'status' => 'active'],
            ['title' => 'Täjirçilik jaýy Daşoguzda',                      'city' => 'Daşoguz',  'price' => 95000, 'rooms' => null,'area' => 200.0,'status' => 'active'],
            ['title' => 'Kottej Balkanabatda',                             'city' => 'Balkanabat','price' => 180000,'rooms' => 6,'area' => 400.0,'status' => 'active'],
            ['title' => '3 otagly kwartira Parahat 4-de',                  'city' => 'Aşgabat', 'price' => 72000, 'rooms' => 3, 'area' => 88.0,  'status' => 'pending'],
            ['title' => 'Ýer bölegi Ak bugdaý etrabyynda',                 'city' => 'Aşgabat', 'price' => 35000, 'rooms' => null,'area' => 600.0,'status' => 'pending'],
            ['title' => 'Ofis Türkmenabat şäherinde',                     'city' => 'Türkmenabat','price' => 65000,'rooms' => null,'area' => 120.0,'status' => 'pending'],
            ['title' => '2 otagly jaý Mary şäherinde',                    'city' => 'Mary',     'price' => 48000, 'rooms' => 2, 'area' => 90.0,  'status' => 'pending'],
            ['title' => 'Kireýine jaý Daşoguzda',                         'city' => 'Daşoguz',  'price' => 600,   'rooms' => 3, 'area' => 75.0,  'status' => 'pending'],
            ['title' => '5 otagly jaý Aşgabatda',                         'city' => 'Aşgabat', 'price' => 320000,'rooms' => 5, 'area' => 450.0, 'status' => 'active'],
            ['title' => 'Ammarhana Balkanabatda',                          'city' => 'Balkanabat','price' => 55000,'rooms' => null,'area' => 350.0,'status' => 'active'],
            ['title' => '1 otagly kwartira Maryde',                       'city' => 'Mary',     'price' => 20000, 'rooms' => 1, 'area' => 38.0,  'status' => 'active'],
            ['title' => 'Täze gurluşyk kwartira Çoganlyda',               'city' => 'Aşgabat', 'price' => 110000,'rooms' => 4, 'area' => 130.0, 'status' => 'pending'],
            ['title' => 'Ýer bölegi Mary welaýatynda',                    'city' => 'Mary',     'price' => 18000, 'rooms' => null,'area' => 2000.0,'status' => 'active'],
        ];

        $categoryMap = [
            'kwartira'    => $categories->where('slug', 'kwartira')->first(),
            'yer'         => $categories->where('slug', 'yer')->first(),
            'ofis'        => $categories->where('slug', 'ofis')->first(),
            'jay'         => $categories->where('slug', 'jay')->first(),
            'kireyine'    => $categories->where('slug', 'kireyine-jay')->first(),
        ];

        foreach ($properties as $i => $data) {
            // Kategoriýany at boýunça bellemek
            $catKey = 'kwartira';
            if (str_contains(mb_strtolower($data['title']), 'ýer') || str_contains(mb_strtolower($data['title']), 'bölegi')) {
                $catKey = 'yer';
            } elseif (str_contains(mb_strtolower($data['title']), 'ofis')) {
                $catKey = 'ofis';
            } elseif (str_contains(mb_strtolower($data['title']), 'kottej') || str_contains(mb_strtolower($data['title']), 'ammarhana') || str_contains(mb_strtolower($data['title']), 'täjirçilik')) {
                $catKey = 'jay';
            } elseif (str_contains(mb_strtolower($data['title']), 'kireý')) {
                $catKey = 'kireyine';
            } elseif (str_contains(mb_strtolower($data['title']), 'jaý') && !str_contains(mb_strtolower($data['title']), 'kireý')) {
                $catKey = 'jay';
            }

            $category = $categoryMap[$catKey] ?? $categories->first();
            $user     = $users->random();

            $property = Property::create([
                'user_id'     => $user->id,
                'category_id' => $category->id,
                'title'       => $data['title'],
                'description' => 'Bu emläk barada doly maglumat. ' . $data['title'] . ' - Amatly ýerleşýär, döwrebap abzallar bilen üpjün edilendir. Giňişleýin maglumat üçin habarlaşyň.',
                'price'       => $data['price'],
                'address'     => $data['city'] . ', ' . rand(1, 50) . '-nji köçe',
                'city'        => $data['city'],
                'rooms'       => $data['rooms'],
                'area'        => $data['area'],
                'status'      => $data['status'],
            ]);

            // Her emläge placeholder surat goşmak
            PropertyImage::create([
                'property_id' => $property->id,
                'image_path'  => 'properties/placeholder.jpg',
                'is_primary'  => true,
            ]);
        }
    }
}
