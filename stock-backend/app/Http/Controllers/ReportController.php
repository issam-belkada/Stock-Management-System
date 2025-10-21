<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\StockMovement;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function stockReport(Request $request)
    {
        $startDate = $request->query('start_date') 
            ? Carbon::parse($request->query('start_date'))->startOfDay()
            : Carbon::now()->startOfMonth();

        $endDate = $request->query('end_date') 
            ? Carbon::parse($request->query('end_date'))->endOfDay()
            : Carbon::now()->endOfDay();

        $movements = StockMovement::with('product')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalIn = $movements->where('type', 'IN')->sum('quantity');
        $totalOut = $movements->where('type', 'OUT')->sum('quantity');

        return response()->json([
            'period' => [
                'start' => $startDate->toDateString(),
                'end' => $endDate->toDateString(),
            ],
            'total_in' => $totalIn,
            'total_out' => $totalOut,
            'movements' => $movements,
        ]);
    }

    /**
     * 🏆 Top-selling products (based on stock OUT)
     */
    public function topProducts(Request $request)
    {
        $startDate = $request->query('start_date') 
            ? Carbon::parse($request->query('start_date'))->startOfDay()
            : Carbon::now()->startOfMonth();

        $endDate = $request->query('end_date') 
            ? Carbon::parse($request->query('end_date'))->endOfDay()
            : Carbon::now()->endOfDay();

        $topProducts = StockMovement::selectRaw('product_id, SUM(quantity) as total_sold')
            ->where('type', 'OUT')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->with('product')
            ->take(10)
            ->get();

        return response()->json([
            'period' => [
                'start' => $startDate->toDateString(),
                'end' => $endDate->toDateString(),
            ],
            'top_products' => $topProducts,
        ]);
    }

    /**
     * 📥 Optional: Export movements as CSV
     */
    public function exportCSV(Request $request)
    {
        $startDate = $request->query('start_date') 
            ? Carbon::parse($request->query('start_date'))->startOfDay()
            : Carbon::now()->startOfMonth();

        $endDate = $request->query('end_date') 
            ? Carbon::parse($request->query('end_date'))->endOfDay()
            : Carbon::now()->endOfDay();

        $movements = StockMovement::with('product')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $csvData = "Product,Type,Quantity,Date\n";
        foreach ($movements as $m) {
            $csvData .= "{$m->product->name},{$m->type},{$m->quantity},{$m->created_at}\n";
        }

        $filename = 'stock_report_' . now()->format('Y_m_d_H_i_s') . '.csv';

        return response($csvData)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename={$filename}");
    }
}
