<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Inertia\Inertia;

class PostController extends Controller
{
    public function index()
    {
        return Inertia::render('blog/infinitescroll/Index', [
            'posts' => Inertia::scroll(fn () => Post::with('category')->latest()->paginate(10)),
        ]);
    }
}