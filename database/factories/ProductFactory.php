<?php

namespace Database\Factories;

use App\Models\Category;
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
            'name' => fake()->word(),
            'slug' => fake()->slug(),
            'thumb_image' => 'https://via.placeholder.com/150x200?text=Product+Thumb',
            'category_id' => function () {
                return Category::inRandomOrder()->first()->id;
            },
            'short_description' => fake()->paragraph(),
            'long_description' => fake()->paragraph(),
            'price' => fake()->randomFloat(2, 10, 200),
            'offer_price' => fake()->randomFloat(2, 1, 100),
            'quantity' => 100,
            'sku' => fake()->unique()->ean13(),
            'seo_description' => fake()->sentence(),
            'show_at_home' => fake()->boolean(),
            'status' => fake()->boolean(),
        ];
    }
}