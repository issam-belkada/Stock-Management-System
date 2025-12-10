<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use PDF; // alias pour Barryvdh\DomPDF\Facade\Pdf si installé
use Carbon\Carbon;

class InvoiceController extends Controller
{
    public function download($id)
    {
        $sale = Sale::with('saleItems.product')->findOrFail($id);

        // données pour la vue
        $company = [
            'name' => 'TechStore DZ',
            'address' => 'Rue de la Liberté, Chlef, Algérie',
            'email' => 'contact@techstore.dz',
            'phone' => '+213 55 55 55 55',
        ];

        $date = Carbon::now()->format('Y-m-d H:i');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('PDF.invoices.show', compact('sale', 'company', 'date'));

        // stream or download
        return $pdf->download("invoice_{$sale->id}.pdf");
        // ou pour afficher dans le navigateur:
        // return $pdf->stream("invoice_{$sale->id}.pdf");
    }
}
