<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

use App\Models\Product;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;
    public function index()
    {
        
        return ProductResource::collection(Product::with('category', 'supplier')->paginate(10));
    }

    public function store(ProductRequest $request)
    {
        $product = Product::create($request->validated());
        return new ProductResource($product);
    }

    public function show($id)
    {
        $product = Product::with('category', 'supplier')->findOrFail($id);
        return new ProductResource($product);
    }


     public function update(ProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->update($request->validated());
        return new ProductResource($product)->additional(['message' => 'Product updated successfully']);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json(['message' => 'Product deleted successfully']);
    }
}
