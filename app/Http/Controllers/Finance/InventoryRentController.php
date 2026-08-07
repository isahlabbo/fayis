<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\InventoryRent;

class InventoryRentController extends Controller
{
    public function receipt($rentId)
    {
        $rent = InventoryRent::with(['item.category', 'teacher.user'])->findOrFail($rentId);

        return view('finance.inventory.rent_receipt', [
            'rent' => $rent,
        ]);
    }
}
