<?php

namespace App\Http\Controllers;

use App\Models\SaleItem;

class SaleItemController extends Controller
{
    public function show($id)
    {
        return SaleItem::with('product')->findOrFail($id);
    }
}
