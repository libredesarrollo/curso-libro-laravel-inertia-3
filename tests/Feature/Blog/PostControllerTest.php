<?php

use App\Models\Category;
use App\Models\Post;

test('index displays paginated posts', function () {
    $category = Category::factory()->create();

    Post::factory()
        ->count(20)
        ->for($category)
        ->create();

    $response = $this->get(route('web.index'));

    $response->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('posts.current_page', 1)
            ->where('posts.per_page', 15)
            ->has('posts.data', 15)
        );
});

test('index returns categories for filters', function () {
    $categories = Category::factory()->count(3)->create();

    $response = $this->get(route('web.index'));

    $response->assertOk()
        ->assertInertia(fn ($page) => $page
            ->has('categories', 3)
        );
});

test('index filters by type', function () {
    $category = Category::factory()->create();

    Post::factory()->for($category)->create(['type' => 'post']);
    Post::factory()->for($category)->create(['type' => 'advert']);

    $response = $this->get(route('web.index', ['type' => 'post']));

    $response->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('filters.type', 'post')
            ->has('posts.data', 1)
        );
});

test('index filters by category', function () {
    $category1 = Category::factory()->create();
    $category2 = Category::factory()->create();

    Post::factory()->for($category1)->create();
    Post::factory()->for($category2)->create();

    $response = $this->get(route('web.index', ['category_id' => $category1->id]));

    $response->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('filters.category_id', (string) $category1->id)
            ->has('posts.data', 1)
        );
});

test('index filters by posted status', function () {
    $category = Category::factory()->create();

    Post::factory()->for($category)->posted()->create();
    Post::factory()->for($category)->notPosted()->create();

    $response = $this->get(route('web.index', ['posted' => 'yes']));

    $response->assertOk()
        ->assertInertia(fn ($page) => $page
            ->has('posts.data', 1)
        );
});

test('index filters by search term', function () {
    $category = Category::factory()->create();

    Post::factory()->for($category)->create(['title' => 'Unique Post Title 123']);
    Post::factory()->for($category)->create(['title' => 'Other Post Title']);

    $response = $this->get(route('web.index', ['search' => 'Unique Post Title 123']));

    $response->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('filters.search', 'Unique Post Title 123')
            ->has('posts.data', 1)
        );
});

test('show displays post with category', function () {
    $category = Category::factory()->create();
    $post = Post::factory()->for($category)->create();

    $response = $this->get(route('web.show', $post));

    $response->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('post.id', $post->id)
            ->where('post.title', $post->title)
            ->where('post.category.id', $category->id)
        );
});

test('show returns 404 for non-existent post', function () {
    $response = $this->get(route('web.show', ['post' => 99999]));

    $response->assertNotFound();
});
