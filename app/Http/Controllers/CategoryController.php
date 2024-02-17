<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CategoryController extends Controller
{
    //index
    public function index(Request $request)
    {
        $categories = Category::paginate(10);
        return view('pages.categories.index', compact('categories'));
    }
    public function create()
    {
        return view('pages.categories.create');
    }
    public function store(Request $request)
    {
        // validate the request...
        $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        // store the request...
        $category = new Category;
        $category->name = $request->name;
        $category->description = $request->description;
        $category->save();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image->storeAs('public/categories', $category->id . '.' . $image->getClientOriginalExtension());
            $category->image = 'public/categories/' . $category->id . '.' . $image->getClientOriginalExtension();
            $category->save();
        }

        return redirect()->route('categories.index')->with('success', 'category created successfully');
    }

    public function show($id)
    {
        return view('pages.categories.show');
    }
    public function edit($id)
    {
        $category = \App\Models\Category::findOrFail($id);
        return view('pages.categories.edit', compact('category'));
    }
    public function update(Request $request, $id)
    {
        // validate the request...
        $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        // store the request...
        $category = Category::find($id);
        $category->name = $request->name;
        $category->description = $request->description;
        $category->save();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image->storeAs('public/categories', $category->id . '.' . $image->getClientOriginalExtension());
            $category->image = 'public/categories/' . $category->id . '.' . $image->getClientOriginalExtension();
            $category->save();
        }

        return redirect()->route('categories.index')->with('success', 'category updated successfully');
    }
    public function destroy(Request $request, $id)
    {
        // delete the request...
        $category = Category::find($id);
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'category deleted successfully');
    }
}
