<?php

namespace App\Http\Controllers\Patron;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SectionClass;

class PerformanceController extends Controller
{
    public function index($sectioClassId) {
        return view('patron.section.performance.index', ['sectionClass'=>SectionClass::find($sectioClassId)]);
    }   
}
