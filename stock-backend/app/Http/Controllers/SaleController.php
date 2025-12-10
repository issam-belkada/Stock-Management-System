<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\Notification;
use App\Models\User;

class SaleController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            // compute total
            $total = 0;
            foreach ($data['items'] as $it) {
                $total += $it['quantity'] * $it['price'];
            }

            $sale = Sale::create([
                'total_amount' => $total,
                'user_id' => Auth::id() ?? null, // adapt if no auth
            ]);

            foreach ($data['items'] as $it) {
                $product = Product::findOrFail($it['product_id']);

                // check stock
                if ($product->stock < $it['quantity']) {
                    throw ValidationException::withMessages([
                        'stock' => "Insufficient stock for product {$product->name}"
                    ]);
                }

                // create sale item
                $saleItem = $sale->saleItems()->create([
                    'product_id' => $product->id,
                    'quantity' => $it['quantity'],
                    'price' => $it['price'],
                    'subtotal' => $it['quantity'] * $it['price'],
                ]);

                // decrement product stock
                $product->decrement('stock', $it['quantity']);

                // create stock movement (OUT)
                StockMovement::create([
                    'product_id' => $product->id,
                    'quantity' => $it['quantity'],
                    'type' => 'OUT',
                    'user_id' => Auth::id() ?? null,
                    'note' => "Sale #{$sale->id}",
                ]);

                // 🔔 NOTIFY ALL USERS IF STOCK IS LOW
                if ($product->stock <= 5) {
        
                    $users = User::all(); // notify everyone
        
                    foreach ($users as $user) {
                        Notification::create([
                            'user_id' => $user->id,
                            'title'   => 'Low Stock Alert',
                            'message' => "{$product->name} has only {$product->stock} items left.",
                        ]);
                    }
                }

            }

            DB::commit();

            return response()->json([
                'message' => 'Sale created successfully',
                'sale_id' => $sale->id,
                'sale' => $sale->load('saleItems.product'),
            ], 201);

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}