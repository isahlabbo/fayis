<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guardian;
use App\Models\SectionClassStudentTerm;

class ResultSearchController extends Controller
{
    public function search(Request $request) {
        $request->validate(['access_code'=>'required']);
        $studentTerm = SectionClassStudentTerm::where('access_code',strtoupper($request->access_code))->first();
        $error = null;

        if(!$studentTerm){
            $error = 'Invalid Access Code';
        }else{
            if(count($studentTerm->studentResults) == 0){
                $error = 'No result available';
            }

            if(!$studentTerm->sectionClassStudentTermResultPublish){
                $error = 'Result is under processing';
            }
        }
        
        if(empty($errors)){

            if($studentTerm->sectionClassStudent->student->guardian->status == 'pending'){
                return redirect()->route('result.guardian',[$studentTerm->id]);
            }else{
                return redirect()->route('result.view',[$studentTerm->id]);
            }
        }

        return redirect()->route('welcome')->withWarning($error);

    }

    public function guardian($studentTermId) {
        return view('auth.guardian',['studentTerm'=>SectionClassStudentTerm::find($studentTermId)]);
    }

    public function view($studentTermId) {
        return view('auth.result',['studentTerm'=>SectionClassStudentTerm::find($studentTermId)]);
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
