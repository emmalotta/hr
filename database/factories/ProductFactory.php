<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Mmo\Faker\PicsumProvider;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        fake()->addProvider(PicsumProvider::class);
        return [
            "name" => $this->faker->sentence(),
            "description" => $this->faker->sentence(),
            "image" => fake()->picsumUrl(400, 400),
            "price" => $this->faker->numberBetween(10, 200),
        ];
    }
}
