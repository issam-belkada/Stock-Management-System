<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockMovementController extends Controller
{
    public function stockIn(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
            'note'       => 'nullable|string|max:255',
        ]);

        $product = Product::findOrFail($request->product_id);
        $product->increment('stock', $request->quantity);

        StockMovement::create([
            'product_id' => $product->id,
            'type' => 'IN',
            'quantity' => $request->quantity,
            'user_id' => Auth::id(),
            'note'    => $request->note,
        ]);

        return response()->json(['message' => 'Stock added successfully']);
    }

    public function stockOut(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'note'     => 'nullable|string|max:255',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->stock < $request->quantity) {
            return response()->json(['error' => 'Insufficient stock'], 400);
        }

        $product->decrement('stock', $request->quantity);

        StockMovement::create([
            'product_id' => $product->id,
            'type' => 'OUT',
            'quantity' => $request->quantity,
            'user_id' => Auth::id(),
            'note'    => $request->note,
        ]);

        // 🔔 Notify Admins & Managers if stock is low
        if ($product->quantity <= 5 ) {
            Notification::create([
                'type' => 'low_stock',
                'message' => "Low stock alert: {$product->name} has {$product->quantity} items left.",
                'for_roles' => json_encode(['Admin', 'Manager']),
            ]);
        }

        return response()->json(['message' => 'Stock deducted successfully']);
    }
}
