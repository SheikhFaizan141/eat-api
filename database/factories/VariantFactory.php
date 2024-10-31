<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Variant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Variant>
 */
class VariantFactory extends Factory
{
    protected $model = Variant::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['Size', 'Quantity']),
            'option' => $this->faker->randomElement(['Small', 'Medium', 'Large', 'Regular', 'Full', 'Half']),
            'price' => $this->faker->randomFloat(2, 5, 20),
        ];
    }
    
}
