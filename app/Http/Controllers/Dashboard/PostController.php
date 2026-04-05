<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
        $post = Post::create($request->validated());

        if ($request->has('image')) {
            $this->upload($request, $post);
        }

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

        if ($request->hasFile('image')) {
            $this->upload($request, $post);
        }

        Inertia::flash('message', 'Post updated successfully.');

        return redirect()->route('post.index');
    }

    public function destroy(Post $post)
    {
        $post->delete();
        Inertia::flash('message', 'Post deleted successfully.');

        return to_route('post.index');
    }

    public function upload(Request $request, Post $post)
    {
        $request->validate([
            'image' => 'required|mimes:jpg,jpeg,png,gif|max:10240',
        ]);

        // dd($request['image']);

        Storage::disk('public_upload')->delete('image/post/' . $post->image);
        $post->update(['image' => '']);
        $data['image'] = $filename = time().'.'.$request['image']->extension();
        $request->image->move(public_path('image/post'), $filename);
        $post->update($data);

        return to_route('post.index')->with('message', 'Upload image to post successfully');
    }

    public function imageDelete(Post $post)
    {
        Storage::disk('public_upload')->delete('image/post/' . $post->image);
        $post->update(['image' => '']);
        return to_route('post.edit', $post->id)->with('message', "image removed to post successfully");
    }
}
