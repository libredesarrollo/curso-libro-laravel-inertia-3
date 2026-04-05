<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->unique()->sentence(6);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'date' => fake()->dateTimeBetween('-1 year', 'now'),
            'image' => fake()->optional()->imageUrl(800, 600, 'post'),
            'description' => fake()->paragraph(),
            'text' => fake()->optional()->paragraphs(5, true),
            'posted' => fake()->randomElement(['yes', 'not']),
            'type' => fake()->randomElement(['advert', 'post', 'course', 'movie']),
            'category_id' => Category::factory(),
        ];
    }

    public function posted(): static
    {
        return $this->state(fn (array $attributes) => [
            'posted' => 'yes',
        ]);
    }

    public function notPosted(): static
    {
        return $this->state(fn (array $attributes) => [
            'posted' => 'not',
        ]);
    }
}
