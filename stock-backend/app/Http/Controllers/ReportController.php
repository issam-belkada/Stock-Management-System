<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;
use App\Models\StockMovement;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

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

        $totalIn = $movements->where('type', 'in')->sum('quantity');
        $totalOut = $movements->where('type', 'out')->sum('quantity');

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

    /**🏆 Top-selling products (based on stock OUT)*/
    
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

    /** Export movements as CSV */

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

    public function exportPDF(Request $request)
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
    
        $data = [
            'period' => [
                'start' => $startDate->toDateString(),
                'end' => $endDate->toDateString(),
            ],
            'total_in' => $movements->where('type', 'in')->sum('quantity'),
            'total_out' => $movements->where('type', 'out')->sum('quantity'),
            'movements' => $movements,
        ];
    
        $pdf = Pdf::loadView('PDF.reports.stock_report', $data);
    
        $filename = "stock_report_" . now()->format('Y_m_d_H_i_s') . ".pdf";
    
        return $pdf->download($filename);
    }


    public function salesReport(Request $request)
{
    $mode = $request->query('mode'); // Mode par défaut : cette semaine

    // Détermination automatique des dates selon le mode
    switch ($mode) {
        case 'today':
            $startDate = Carbon::today()->startOfDay();
            $endDate = Carbon::today()->endOfDay();
            break;

        case 'month':
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfDay();
            break;

        case 'custom':
            $startDate = $request->query('start_date')
                ? Carbon::parse($request->query('start_date'))->startOfDay()
                : Carbon::now()->startOfWeek();

            $endDate = $request->query('end_date')
                ? Carbon::parse($request->query('end_date'))->endOfDay()
                : Carbon::now()->now()->endOfDay();
            break;

        case 'week':
        default:
            $startDate = Carbon::now()->startOfWeek();
            $endDate = Carbon::now()->endOfDay();
            break;
    }

    // Récupération des ventes filtrées par date
    $sales = Sale::with('saleItems.product')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->orderBy('created_at', 'desc')
        ->get();

    // Total général du chiffre d’affaires
    $totalSales = $sales->sum('total_amount');

    // Total quantité vendue (si tu en as besoin)
    $totalQuantity = $sales->flatMap->products->sum('pivot.quantity');

    // Préparation des données PDF
    $data = [
        'sales' => $sales,
        'totalSales' => $totalSales,
        'totalQuantity' => $totalQuantity,
        'mode' => $mode,
        'period' => [
            'start' => $startDate->toDateString(),
            'end' => $endDate->toDateString(),
        ],
    ];

    $pdf = PDF::loadView('PDF.reports.sales', $data)->setPaper('a4', 'portrait');

    return $pdf->download('rapport_ventes.pdf');
}


}
