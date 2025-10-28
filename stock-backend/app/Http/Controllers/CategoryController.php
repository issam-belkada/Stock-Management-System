<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

use App\Models\Category;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;

class CategoryController extends Controller
{
        use AuthorizesRequests, ValidatesRequests;

    public function index()
    {
        $categories = Category::with('products')->paginate(10);
        return CategoryResource::collection($categories);
    }


    public function store(CategoryRequest $request)
    {
        $category = Category::create(attributes: $request->validated());
        return new CategoryResource($category)->additional(['message' => 'Category created successfully']);
    }

    public function show($id)
    {
        $category = Category::with('products')->findOrFail($id);
        return new CategoryResource($category);
    }

    public function update(CategoryRequest $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->update($request->validated());
        return new CategoryResource($category)->additional(['message' => 'Category updated successfully']);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return response()->json(['message' => 'Category deleted successfully']);
    }
}
