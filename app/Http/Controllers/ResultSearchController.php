<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guardian;
use App\Models\SectionClassStudentTerm;

class ResultSearchController extends Controller
{
    public function check($error = null) {
        return view('auth.result.check', compact('error'));
    }

    public function search(Request $request) {
        $request->validate(['access_code'=>'required']);
        $studentTerm = SectionClassStudentTerm::where('access_code',strtoupper($request->access_code))->first();
        
        $error = null;
        // check if the result is published
       

        if(!$studentTerm){
            $error = 'Invalid Access Code';
        }else if($studentTerm->sectionClassStudentTermResultPublish && $studentTerm->sectionClassStudentTermResultPublish->position){
            
            if(count($studentTerm->studentResults) == 0){
                $error = 'No subject results found';
            }

        }else{
            $error = 'Result is under processing';
        }
        
        if(!$error){

            if($studentTerm->sectionClassStudent->student->guardian->status == 'pending'){
                return redirect()->route('result.guardian',[$studentTerm->id]);
            }else{
                return redirect()->route('result.view',[$studentTerm->id]);
            }
        }
        // set error message and redirect back to check page

        return redirect()->route('result.check')->with('error', $error);

    }

    public function guardian($studentTermId) {
        return view('auth.result.guardian',['studentTerm'=>SectionClassStudentTerm::find($studentTermId)]);
    }

    public function view($studentTermId) {
        return view('auth.result.view',['studentTerm'=>SectionClassStudentTerm::find($studentTermId)]);
    }

    public function updateGuardian(Request $request, $guardianId, $studentTermId) {
        $request->validate([
            'name'=>'required',
            'phone'=>'required',
            'address'=>'required',
        ]);
        $guardian = Guardian::find($guardianId);
        $guardian->update([
            'name'=>$request->name,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'address'=>$request->address,
            'status'=>'updated',
        ]);

        return redirect()->route('result.view',[$studentTermId]);
    }
}
