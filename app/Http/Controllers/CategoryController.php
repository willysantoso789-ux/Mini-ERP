<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::where('user_id', auth()->id())->orderByRaw("
        CASE 
            WHEN type = 'income' THEN 1
            WHEN type = 'expense' THEN 2
        END
        ")->get();
        
        return view('category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $icons = ['🍔','🚗','🏠','💡','🛍','🎮','💰','📈'];
        return view('category.create', compact('icons'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        Category::create($request->validated());

        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $category = Category::where('user_id', auth()->id())->findOrFail($id);
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $category = Category::where('user_id', auth()->id())->findOrFail($id);
        if ($category->is_system) {
            return redirect()->route('categories.index')->with('error', 'System categories cannot be edited.');
        }

        $icons = ['🍔','🚗','🏠','💡','🛍','🎮','💰','📈'];
        return view('category.edit', compact('category','icons')); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, $id)
    {
        $category = Category::where('user_id', auth()->id())->findOrFail($id);
        $category->update($request->validated());

        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = Category::where('user_id', auth()->id())->findOrFail($id);
        if ($category->is_system) {
            return redirect()->route('categories.index')->with('error', 'System categories cannot be deleted.');
        }

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }
}
