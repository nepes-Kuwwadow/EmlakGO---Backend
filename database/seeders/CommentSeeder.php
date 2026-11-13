<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $properties = Property::take(5)->get();
        $admin = User::where('role', 'admin')->first();

        $sampleComments = [
            [
                'name' => 'Merdan Amanow',
                'comment' => 'Örän arassa we giň jaý ekeni. Ýerleşýän ýeri hem gaty amatly, mekdep we duralga golaý.',
                'rating' => 5,
            ],
            [
                'name' => 'Aýgül Saparowa',
                'comment' => 'Bahasy beýleki jaýlara görä örän amatly. Eýesi bilen jaňlaşyp gürleşdik, resminamalary arassa.',
                'rating' => 5,
            ],
            [
                'name' => 'Döwlet Berdiýew',
                'comment' => 'Remonty gowy, lift işleýär. Ýöne awtoduralga ýeri azrak ekeni.',
                'rating' => 4,
            ],
        ];

        foreach ($properties as $property) {
            foreach ($sampleComments as $sample) {
                Comment::create([
                    'property_id' => $property->id,
                    'user_id'     => $admin ? $admin->id : null,
                    'name'        => $sample['name'],
                    'comment'     => $sample['comment'],
                    'rating'      => $sample['rating'],
                    'is_approved' => true,
                ]);
            }
        }
    }
}
