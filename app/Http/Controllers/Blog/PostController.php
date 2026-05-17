<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PostController extends Controller
{
    function index()
    {
        // $posts = Post::with('category')
        //     ->filterDataTable(request()->only(['type', 'category_id', 'posted', 'search', 'from', 'to', 'sortColumn', 'sortDirection']))
        //     ->paginate(15)
        //     ->withQueryString();

        return inertia('blog/Index', [
            // 'posts' => $posts,
        //     'posts' => Inertia::optional(function () {
        //     return Post::with('category')
        //     ->filterDataTable(request()->only(['type', 'category_id', 'posted', 'search', 'from', 'to', 'sortColumn', 'sortDirection']))
        //     ->paginate(15)
        //     ->withQueryString();
        // }),
        'posts' => Inertia::defer(function () {
            sleep(2); // Simulamos una consulta pesada
            return 
                Post::with('category')
            // ->filterDataTable(request()->only(['type', 'category_id', 'posted', 'search', 'from', 'to', 'sortColumn', 'sortDirection']))
            ->paginate(15)
            ->withQueryString()
            ;
        }),
        
            'categories' => Category::orderBy('title')->get(['id', 'title']),
            // 'categories' => Inertia::once(function () {
            // return Category::orderBy('title')->get(['id', 'title']);
        // }),
            'filters' => request()->only([/*'posted',*/ 'type', 'category_id', 'search', 'to', 'from', 'sortColumn', 'sortDirection']),
        ]);

    }
    function show(Post $post)
    {
        $post->category;
        return inertia('blog/Show', compact('post'));
    }

    public function indexinfinitescroll()
    {
        return Inertia::render('blog/infinitescroll/Index', [
            'posts' => Inertia::scroll(fn () => Post::with('category')->latest()->paginate(10)),
        ]);
    }
}
