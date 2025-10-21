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
        $this->authorize('manage_products');
        $product = Product::create($request->validated());
        return new ProductResource($product);
    }

    public function show(Product $product)
    {

        return new ProductResource($product->load('category', 'supplier'));
    }

    public function update(ProductRequest $request, Product $product)
    {
        $product->update($request->validated());
        return new ProductResource($product);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['message' => 'Product deleted successfully']);
    }
}
