<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Variant;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // $product = Product::create(['title' => 'Vegan Pizza', 'description' => 'Delicious vegan pizza', 'price' => 10.99]);
        // $category1 = Category::create(['name' => 'Pizza']);
        // $category2 = Category::create(['name' => 'Vegan']);

        // $product->categories()->attach([$category1->id, $category2->id]);


        // Create 5 categories
        // $categories = Category::factory(5)->create();

        // Create 10 products and assign random categories to each
        // Product::factory(10)->create()->each(function ($product) use ($categories) {
        //     // Attach 1-3 random categories to each product
        //     $product->categories()->attach($categories->random(rand(1, 3))->pluck('id')->toArray());
        // });
        // Product::factory(10)->create()->each(fn($product) => $product->categories()->attach($categories->random(rand(1, 3))->pluck('id')->toArray()))->each(fn($product) => $product->var);

        // Product::factory()->count(10)->create();

        // Working
        $categories = Category::factory(5)->create();
        $products = Product::factory()->count(5)->create();

        // $products->each(function ($product) use ($categories) {
        //     $product->categories()->attach($categories->random(\rand(1, 4))->pluck('id')->toArray());
        //     Variant::factory()->count(3)->create([
        //         'product_id' => $product->id,
        //     ]);
        // });


        $products
            ->each(fn($product) => $product->categories()
                ->attach($categories->random(1)
                    ->pluck('id')->toArray()))
            ->each(fn($product) => Variant::factory(2)->create(['product_id' => $product->id]));
    }
}
