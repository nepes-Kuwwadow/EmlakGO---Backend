<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Property>
 */
class PropertyFactory extends Factory
{
    protected $model = Property::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cities = ['Aşgabat', 'Mary', 'Türkmenabat', 'Daşoguz', 'Balkanabat', 'Türkmenbaşy'];

        return [
            'user_id'     => User::factory(),
            'category_id' => Category::inRandomOrder()->first()?->id ?? Category::factory(),
            'title'       => $this->faker->sentence(4),
            'description' => $this->faker->paragraphs(2, true),
            'price'       => $this->faker->randomFloat(2, 10000, 500000),
            'address'     => $this->faker->streetAddress(),
            'city'        => $this->faker->randomElement($cities),
            'rooms'       => $this->faker->optional()->numberBetween(1, 6),
            'area'        => $this->faker->optional()->randomFloat(2, 30, 500),
            'status'      => $this->faker->randomElement(['active', 'pending', 'rejected']),
        ];
    }

    /**
     * Aktif emläk ýagdaýy
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'active']);
    }

    /**
     * Garaşylýan emläk ýagdaýy
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'pending']);
    }
}
