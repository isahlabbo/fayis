<?php

namespace App\Http\Controllers\Patron;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Charts\TeachersComparisonSubjectClassChart;
use App\Charts\TeacherEffectivenessIndexChart;
use App\Charts\TermVsClassAverageChart;
use App\Charts\TermlySubjectEvaluationChart;

class AnalysisController extends Controller
{
    public function index() {
        
        return view('patron.analysis.index');
    }

    function search(Request $request) {
        $request->validate([
            "session" => "required",
            "term" => "required",
            "section" => "required",
            "analysis" => "required"
        ]);
        switch ($request->analysis) {
            case '1':
                $chart = new TeacherEffectivenessIndexChart($request->section, $request->session, $request->term);
                break;
            case '2':
                $chart = new TermlySubjectEvaluationChart($request->section, $request->session, $request->term);
                break;
            case '3':
                $chart = new TermVsClassAverageChart($request->section, $request->session, $request->term);
                break;
            case '4':
                $chart = new TeachersComparisonSubjectClassChart($request->section, $request->session, $request->term);
                break;
            
            default:
                abort(404, 'Invalid analysis type');
                break;
        }

        $chart->build();
        return view('patron.analysis.view',['chart'=>$chart]);
    }
}
