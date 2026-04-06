<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Inertia\Inertia;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::filterDataTable(request()->only([ 'search', 'sortColumn', 'sortDirection']))
            ->paginate(15)
            ->withQueryString();

        return inertia('dashboard/category/Index',compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $category = new Category();
        
        return inertia('dashboard/category/Save',compact('category'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        Category::create(
            $request->validated()
        );

        Inertia::flash('message', 'Record created successfully.');

        return to_route('category.index');
    }

    // /**
    //  * Display the specified resource.
    //  */
    // public function show(Category $category)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        // return inertia('dashboard/category/Edit',['category' => $category']);
        return inertia('dashboard/category/Save',compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $category->update($request->validated());

        Inertia::flash('message', 'Record updated successfully.');

        return redirect()->route('category.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        Inertia::flash('message', 'Record deleted successfully.');
        return to_route('category.index');
    }
}
