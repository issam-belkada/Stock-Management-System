<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with('saleItems.product')->latest()->get();
        return response()->json($sales);
    }

    public function show($id)
    {
        $sale = Sale::with('saleItems.product')->findOrFail($id);
        return response()->json($sale);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:1',
        ]);

        return DB::transaction(function () use ($validated) {
            $total = 0;

            // Create the sale
            $sale = Sale::create([
                'user_id' => auth()->id(),
                'total_amount' => 0
            ]);

            foreach ($validated['items'] as $item) {
                $product = Product::find($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Not enough stock for product: {$product->name}");
                }

                // Update stock
                $product->stock -= $item['quantity'];
                $product->save();

                $subtotal = $product->price * $item['quantity'];
                $total += $subtotal;

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $subtotal
                ]);
            }

            $sale->update(['total_amount' => $total]);

            return response()->json([
                'message' => 'Sale recorded successfully.',
                'sale' => $sale->load('saleItems.product')
            ]);
        });
    }
}
