<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('id', 'desc')->get();
        return response()->json($categories, 201);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $this->validate($request, [
            'name' => 'required|unique:categories|max:25'
        ]);
        $data['name'] = $request->name;
        $data['slug'] = Str::slug($request->name);
        $data['is_active'] = 1;
        Category::create($data);
        return response()->json('success', 201);
    }

    public function show(Category $category)
    {
        return response()->json($category, 201);
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->all();
        $this->validate($request, [
            'name' => 'required|max:25|unique:categories,name,' . $category->id . 'id'
        ]);
        $data['name'] = $request->name;
        $data['slug'] = Str::slug($request->name);
        $data['is_active'] = 1;
        $category->update($data);
        return response()->json('success', 201);
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json('success', 201);
    }


}
