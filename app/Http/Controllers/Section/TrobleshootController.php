<?php

namespace App\Http\Controllers\Section;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Section;
use App\Models\SectionClass;

class TrobleshootController extends Controller
{
    public function index($sectionId)
    {
        return view('section.trobleshoot.index',['section'=>Section::find($sectionId)]);
    }

    public function classReport($sectionClassId)
    {
        return view('section.trobleshoot.class',['sectionClass'=>SectionClass::find($sectionClassId)]);
    }

    public function fixedIssue($sectionClassId, $status)
    {
        $sectionClass= SectionClass::find($sectionClassId);
        switch ($status) {
            case '1':
                # regenerate admission number of all students in this class
                $sectionClass->updateAdmissionNo();
                break;
            case '2':
                # 
                break;
            case '3':
                # create all section class student term for first, second and third term for all student in this class
                
                foreach($sectionClass->sectionClassStudents as $sectionClassStudent){
                    foreach ($sectionClass->currentSession()->academicSessionTerms as $academicSessionTerm) {
                        $sectionClassStudent->sectionClassStudentTerms()->firstOrCreate(['academic_session_term_id'=>$academicSessionTerm->id]);
                    }
                }
                break;
            case '4':
                # update all student current term in this class
                    $sectionClass->updateAllStudentTerm();
                break;
            case '5':
                # code...
                break;
            case '6':
                foreach($sectionClass->sectionClassStudents as $sectionClassStudent){
                    foreach ($sectionClassStudent->sectionClassStudentTerms as $sectionClassStudentTerm) {
                        if($sectionClassStudentTerm->academicSessionTerm->term->id == $sectionClassStudentTerm->currentSessionTerm()->term->id){
                            $sectionClassStudentTerm->update(['status'=> 'Active']);
                        }else{
                            $sectionClassStudentTerm->update(['status'=> 'Not Active']);
                        }
                    }
                }
                break;
            default:
                # code...
                break;
        }
        return redirect()->route('dashboard.section.trobleshoot.class',[$sectionClassId])->withSuccess('Issue Fixed');
    }
}
