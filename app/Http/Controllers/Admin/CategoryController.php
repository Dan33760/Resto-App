<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\CategoryStoreRequest;

class CategoryController extends Controller
{
    // Index
    public function index()
    {
        $categories = Category::all();
        return view('admin.categories.index', compact('categories'));
    }

    // Create
    public function create()
    {
        return view('admin.categories.create');
    }

    // Store
    public function store(CategoryStoreRequest $request)
    {
        $image = $request->file('image')->store('public/categories');

        Category::create([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $image
        ]);

        return to_route('admin.categories.store')->with('success', 'Category created successfully');
    }

    // Show
    public function show($id)
    {
        //
    }

    // Edit
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    // Update
    public function update(Request $request, Category $category)
    {

        $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);
        $image = $category->image;

        if($request->hasFile('image')){
            Storage::delete($category->image);
            $image = $request->file('image')->store('public/categories');
        }

        $category->update([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $image,
        ]);

        return to_route('admin.categories.store')->with('success', 'Category updated successfully');

    }

    // Destroy
    public function destroy(Category $category)
    {
        Storage::delete($category->image);
        $category->menus()->detach();
        $category->delete();

        return to_route('admin.categories.store')->with('danger', 'Category deleted successfully');
    }
}
