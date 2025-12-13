<?php

namespace App\Http\Controllers\Patron;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Section;
use App\Charts\TermlySubjectEvaluation;

class SectionController extends Controller
{
    public function index($sectionId) {
        $chart = new TermlySubjectEvaluation($sectionId);
        $chart->build();
        return view('patron.section.index', ['chart'=>$chart,'section'=>Section::find($sectionId)]);
    }
}
