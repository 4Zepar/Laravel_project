<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

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
        return [
            'name' => 'Gaming ' . $this->faker->word(),
            'description' => $this->faker->text(100),
            'price' => $this->faker->numberBetween(1000, 50000), 
            'image' => 'https://placehold.co/600x400',
            'category_id' => 1, 
        ];
    }
}
