<?php

namespace App\Http\Controllers\School;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AcademicSession;

class TeachingEvaluationController extends Controller
{
    public function index($sessionId)
    {
        return view('school.teacher.evaluation',['session'=>AcademicSession::find($sessionId)]);
    }
}
