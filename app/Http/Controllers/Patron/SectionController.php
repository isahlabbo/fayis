<?php

namespace App\Http\Controllers\Patron;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Section;

class SectionController extends Controller
{
    public function index($sectionId) {
        return view('patron.section.index', ['section'=>Section::find($sectionId)]);
    }
}
