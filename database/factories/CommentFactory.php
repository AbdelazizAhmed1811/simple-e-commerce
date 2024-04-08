<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'creator_id' => User::factory(),
            'product_id' => Product::factory(),
            'comment' => fake()->sentence(),
            'like_' => fake()->numberBetween(0, 10),
            'dislike' => fake()->numberBetween(0, 10),
        ];
    }
}
