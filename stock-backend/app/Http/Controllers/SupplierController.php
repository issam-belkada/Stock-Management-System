<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

use App\Models\Supplier;
use App\Http\Requests\SupplierRequest;
use App\Http\Resources\SupplierResource;

class SupplierController extends Controller
{
        use AuthorizesRequests, ValidatesRequests;

    public function index()
    {
        return SupplierResource::collection(Supplier::latest()->paginate(10));
    }

    public function store(SupplierRequest $request)
    {

        $supplier = Supplier::create($request->validated());
        return new SupplierResource($supplier)->additional(['message' => 'Supplier created successfully']);
    }

    public function show( $id)
    {
        $supplier = Supplier::findOrFail($id);
        return new SupplierResource($supplier);
    }

    public function update(SupplierRequest $request, $id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->update($request->validated());
        return new SupplierResource($supplier)->additional(['message' => 'Supplier updated successfully']);
    }

    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();
        return response()->json(['message' => 'Supplier deleted successfully']);
    }
}
