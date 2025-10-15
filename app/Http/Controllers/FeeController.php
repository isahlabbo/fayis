<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FeeController extends Controller
{
    public function review()
    {
        return view('epayment.schoolfee.review');
    }
}
