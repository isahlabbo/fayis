<?php

namespace App\Http\Controllers\Patron;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Charts\TeachersComparisonSubjectClassChart;
use App\Charts\TeacherEffectivenessIndexChart;
use App\Charts\TermVsClassAverageChart;
use App\Charts\TermlySubjectEvaluationChart;
use App\Models\TermlyClassAveraging;
use App\Models\TermlySubjectEvaluation;
use App\Models\TermlyTeacherEffectiveIndex;
use App\Models\TeachersClassSubjectComparison;

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

        $session = $request->session;
        $term = $request->term;
        $section = $request->section;

        switch ($request->analysis) {
            case '1':
                $chart = new TeacherEffectivenessIndexChart($request->section, $request->session, $request->term);
                $data = TermlyTeacherEffectiveIndex::query()
                    ->when($session, fn($q) => $q->where('academic_session_id', $session))
                    ->when($term, fn($q) => $q->where('term_id', $term))
                    ->when($section, fn($q) => $q->where('section_id', $section))
                    ->get();
                    $dataTable = 'patron.analysis.includes.teacherEffectiveness';
                break;
            case '2':
                $chart = new TermlySubjectEvaluationChart($request->section, $request->session, $request->term);
                $data = TermlySubjectEvaluation::query()
                    ->when($session, fn($q) => $q->where('academic_session_id', $session))
                    ->when($term, fn($q) => $q->where('term_id', $term))
                    ->when($section, fn($q) => $q->where('section_id', $section))
                    ->get();
                $dataTable = 'patron.analysis.includes.subjectEvaluation';
                break;
            case '3':
                $chart = new TermVsClassAverageChart($request->section, $request->session, $request->term);
                $data = TermlyClassAveraging::query()
                    ->when($session, fn($q) => $q->where('academic_session_id', $session))
                    ->when($term, fn($q) => $q->where('term_id', $term))
                    ->when($section, fn($q) => $q->where('section_id', $section))
                    ->get();
                    $dataTable = 'patron.analysis.includes.classAverage';
                break;
            case '4':
                $chart = new TeachersComparisonSubjectClassChart($request->section, $request->session, $request->term);
                $data = TeachersClassSubjectComparison::query()
                    ->when($session, fn($q) => $q->where('academic_session_id', $session))
                    ->when($term, fn($q) => $q->where('term_id', $term))
                    ->when($section, fn($q) => $q->where('section_id', $section))
                    ->get();
                    $dataTable = 'patron.analysis.includes.subjectClassCompare';
                break;
            
            default:
                abort(404, 'Invalid analysis type');
                break;
        }


        $chart->build();
        return view('patron.analysis.view',[
            'chart'=>$chart,
            'data'=>$data,
            'analysisType'=>$request->analysis,
            'dataTable'=>$dataTable
        ]);
    }
}
