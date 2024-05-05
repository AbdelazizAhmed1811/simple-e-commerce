<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Schema;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;


    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'description' => fake()->sentence(),
            'product_type' => fake()->name(),
            'price' => fake()->numberBetween(0, 5000),
            'in_stock' => fake()->numberBetween(0, 100),
            'category' => fake()->name(),
        ];
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
}
