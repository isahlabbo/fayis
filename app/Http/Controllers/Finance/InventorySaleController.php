<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\InventorySale;

class InventorySaleController extends Controller
{
    public function receipt($saleId)
    {
        $sale = InventorySale::with(['saleItems.item.category', 'student.student', 'student.sectionClass'])->findOrFail($saleId);

        return view('finance.inventory.sale_receipt', [
            'sale' => $sale,
        ]);
    }
}
