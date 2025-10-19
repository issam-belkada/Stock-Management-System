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
        $this->authorize('view_categories');
        return CategoryResource::collection(Category::latest()->paginate(10));
    }

    public function store(CategoryRequest $request)
    {
        $this->authorize('manage_categories');
        $category = Category::create($request->validated());
        return new CategoryResource($category);
    }

    public function show(Category $category)
    {
        $this->authorize('view_categories');
        return new CategoryResource($category);
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $this->authorize('manage_categories');
        $category->update($request->validated());
        return new CategoryResource($category);
    }

    public function destroy(Category $category)
    {
        $this->authorize('manage_categories');
        $category->delete();
        return response()->json(['message' => 'Category deleted successfully']);
    }
}
