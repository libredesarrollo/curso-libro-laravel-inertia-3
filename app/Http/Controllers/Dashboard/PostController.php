<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Models\Category;
use App\Models\Post;
use Inertia\Inertia;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('category')->paginate(10);

        return inertia('dashboard/post/Index', compact('posts'));
    }

    public function create()
    {
        $post = new Post;
        $categories = Category::orderBy('title')->get(['id', 'title']);

        return inertia('dashboard/post/Save', compact('post', 'categories'));
    }

    public function store(PostRequest $request)
    {
        Post::create($request->validated());

        Inertia::flash('message', 'Post created successfully.');

        return to_route('post.index');
    }

    public function edit(Post $post)
    {
        $categories = Category::orderBy('title')->get(['id', 'title']);

        return inertia('dashboard/post/Save', compact('post', 'categories'));
    }

    public function update(PostRequest $request, Post $post)
    {
        $post->update($request->validated());

        Inertia::flash('message', 'Post updated successfully.');

        return redirect()->route('post.index');
    }

    public function destroy(Post $post)
    {
        $post->delete();
        Inertia::flash('message', 'Post deleted successfully.');

        return to_route('post.index');
    }
}
