<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\StockMovement;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // General stats
        $totalProducts = Product::count();
        $totalSuppliers = Supplier::count();
        $totalStockIn = StockMovement::where('type', 'IN')->sum('quantity');
        $totalStockOut = StockMovement::where('type', 'OUT')->sum('quantity');

        // Low stock alerts
        $lowStockProducts = Product::where('stock', '<=', 5)->get();

        // Recent stock movements (last 10)
        $recentMovements = StockMovement::with('product')
            ->latest()
            ->take(10)
            ->get();

        // Optional: stats per month
        $stockInByMonth = StockMovement::selectRaw('MONTH(created_at) as month, SUM(quantity) as total')
            ->where('type', 'IN')
            ->groupBy('month')
            ->pluck('total', 'month');

        $stockOutByMonth = StockMovement::selectRaw('MONTH(created_at) as month, SUM(quantity) as total')
            ->where('type', 'OUT')
            ->groupBy('month')
            ->pluck('total', 'month');

        return response()->json([
            'total_products' => $totalProducts,
            'total_suppliers' => $totalSuppliers,
            'total_stock_in' => $totalStockIn,
            'total_stock_out' => $totalStockOut,
            'low_stock' => $lowStockProducts,
            'recent_movements' => $recentMovements,
            'monthly_in' => $stockInByMonth,
            'monthly_out' => $stockOutByMonth,
        ]);
    }
}
