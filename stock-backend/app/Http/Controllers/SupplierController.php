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
        $this->authorize('view_suppliers');
        return SupplierResource::collection(Supplier::latest()->paginate(10));
    }

    public function store(SupplierRequest $request)
    {
        $this->authorize('manage_suppliers');
        $supplier = Supplier::create($request->validated());
        return new SupplierResource($supplier);
    }

    public function show(Supplier $supplier)
    {
        $this->authorize('view_suppliers');
        return new SupplierResource($supplier);
    }

    public function update(SupplierRequest $request, Supplier $supplier)
    {
        $this->authorize('manage_suppliers');
        $supplier->update($request->validated());
        return new SupplierResource($supplier);
    }

    public function destroy(Supplier $supplier)
    {
        $this->authorize('manage_suppliers');
        $supplier->delete();
        return response()->json(['message' => 'Supplier deleted successfully']);
    }
}
