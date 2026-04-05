<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\TagRequest;
use App\Models\Tag;
use Inertia\Inertia;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::paginate(15);

        return inertia('dashboard/tag/Index', compact('tags'));
    }

    public function create()
    {
        $tag = new Tag;

        return inertia('dashboard/tag/Save', compact('tag'));
    }

    public function store(TagRequest $request)
    {
        Tag::create($request->validated());

        Inertia::flash('message', 'Tag created successfully.');

        return to_route('tag.index');
    }

    public function edit(Tag $tag)
    {
        return inertia('dashboard/tag/Save', compact('tag'));
    }

    public function update(TagRequest $request, Tag $tag)
    {
        $tag->update($request->validated());

        Inertia::flash('message', 'Tag updated successfully.');

        return redirect()->route('tag.index');
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();
        Inertia::flash('message', 'Tag deleted successfully.');

        return to_route('tag.index');
    }
}
