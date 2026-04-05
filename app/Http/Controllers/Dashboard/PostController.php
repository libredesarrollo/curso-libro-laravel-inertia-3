<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class PostController extends Controller
{
    public function index()
    {
        // $posts = Post::with('category')->paginate(15)->withQueryString();
        
        // $posts = Post::where("id", ">=", 1);
        // $posts = Post::query();
       
        // if(request()->filled('posted')){
        //     $posts->where('posted', request('posted'));
        // }
        // if( request()->filled('category_id')){
        //     $posts->where('category_id', request('category_id'));
        // }
    
        // if( request()->filled('type')){
        //     $posts->where('type', request('type'));
        // }
        
        // //dd($posts->with('category')->toSql());
        // $posts = $posts->with('category')->paginate(15)->withQueryString();

        $posts = Post::with('category')
            ->when(request('type'), function(Builder $query, $type) {
                $query->where('type', $type);
            })
            ->when(request('category_id'), function(Builder $query, $category_id) {
                $query->where('category_id', $category_id);
            })
            ->when(request('posted'), function(Builder $query, $posted) {
                $query->where('posted', $posted);
            })
            ->when(request('search'), function (Builder $query, string $search) {
                $query->where(function ($query) use ($search) {
                    $query->orWhere('id', 'like', "%" . $search . "%")
                        ->orWhere('title', 'like', "%" . $search . "%")
                        ->orWhere('description', 'like', "%" . $search . "%");
                });
            })
            ->when(request('from'), function($q, $from) {
                $q->whereDate('date', '>=', $from);
            })
            ->when(request('to'), function($q, $to) {
                $q->whereDate('date', '<=', $to);
            })
            ->paginate(15)->withQueryString();
            // dd($posts->toSql());
            //


        return inertia('dashboard/post/Index',[
        'posts' => $posts,
        'categories' => Category::orderBy('title')->get(['id', 'title']),
        'filters' => request()->only(['posted', 'type', 'category_id','search', 'to', 'from']), // Enviamos los filtros de vuelta
    ]);
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
