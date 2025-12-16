<?php

namespace App\Http\Controllers\Patron;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Charts\TeachersComparisonSubjectClassChart;

class AnalysisController extends Controller
{
    public function teaching() {
        $chart = new TeachersComparisonSubjectClassChart(1);
        $chart->build();
        return view('patron.analysis.teaching', ['chart'=>$chart]);
    }
}
