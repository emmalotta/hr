<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Mmo\Faker\PicsumProvider;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subject>
 */
class SubjectFactory extends Factory
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
            "title" => fake()->sentence(2),
            "image" => fake()->picsumUrl(400, 480),
            "description" => fake()->paragraph(3),
            "ranking" => fake()->unique()->numberBetween(1, 10),
            "price" => fake()->numberBetween(1, 100),
        ];
    }
}
