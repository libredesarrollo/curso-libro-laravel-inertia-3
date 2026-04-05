<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        Category::factory(5)->create()->each(function ($category) {
            Post::factory(10)->create(['category_id' => $category->id])->each(function ($post) {
                // $post->tags()->attach();
            });
        });

        Tag::factory(30)->create()->pluck('id');
    }
}
