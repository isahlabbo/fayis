<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\InventoryUsage;

class InventoryUsageController extends Controller
{
    public function receipt($usageId)
    {
        $usage = InventoryUsage::with(['item.category', 'stock', 'student.student', 'teacher'])->findOrFail($usageId);

        return view('finance.inventory.receipt', [
            'usage' => $usage,
        ]);
    }
}
