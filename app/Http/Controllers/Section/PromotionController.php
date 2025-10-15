<?php

namespace App\Http\Controllers\Section;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SectionClass;

class PromotionController extends Controller
{
    public function index()
    {
        return view('section.class.promotion');
    }

    public function promoteAllStudent($sectionClassId)
    {
        foreach (SectionClass::find($sectionClassId)
            ->sectionClassStudents
            ->where('status','Active') as $sectionClassStudent) {
            $sectionClassStudent->promoteToNextClass();
        }
        return redirect()->route('dashboard.section.promotion.index')
        ->withSuccess('Promotion was Successfully Compilled');
    }
}
