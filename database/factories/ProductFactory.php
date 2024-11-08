<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{

    protected $model = Product::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array

    {
        return [
            'title' => fake()->word(),
            'description' => fake()->sentence(),
            'image_path' => fake()->imageUrl(480, 480, fake()->randomLetter()),
            'price' => fake()->randomFloat(2, 5, 100), // Price between 5 and 100
            'sale_price' => fake()->randomFloat(2, 5, 100), // Price between 5 and 100

        ];
    }

    
}
