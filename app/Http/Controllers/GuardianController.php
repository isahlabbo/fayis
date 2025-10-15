<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
use App\Models\Guardian;
use App\Notifications\AccountCreated;
use App\Services\Upload\FileUpload;
use App\Events\ChildLinked;
use Auth;

class GuardianController extends Controller
{
    use FileUpload;

    public function update(Request $request, $userId)
    {
        $request->validate([
            'residence_address'=> 'required',
            'office_address'=> 'required',
            'residence_phone'=> 'required',
            'office_phone'=> 'required',
            'occupation'=> 'required',
            'gsm'=> 'required'
        ]);

        $user = User::find($userId);

        if($user->guardian){
            $user->guardian->update([
                'residence_address'=> $request->residence_address,
                'contact_address'=> $request->office_address,
                'residence_phone'=> $request->residence_phone,
                'office_phone'=> $request->office_phone,
                'occupation'=> $request->occupation,
                'gsm'=> $request->gsm
            ]);
        }else{
            $guardian = $user->guardian()->create([
                'residence_address'=> $request->residence_address,
                'contact_address'=> $request->office_address,
                'residence_phone'=> $request->residence_phone,
                'office_phone'=> $request->office_phone,
                'occupation'=> $request->occupation,
                'gsm'=> $request->gsm
            ]);
            
            $guardian->notify(new AccountCreated());   
        }
        
        return redirect()->route('dashboard')->withSuccess('Profile Updated & the SMS was sent to you via GSM Number provided');
    }

    public function verifyChild(Request $request)
    {
        $request->validate(['admission_no'=>'required']);
        $student = Student::where('admission_no', $request->admission_no)->first();
        
        if($student){
            if($student->guardian){
                return redirect()->route('dashboard')->withWarning('This child, has already link to another guardian');
            }
            return redirect()->route('dashboard.guardian.child.verified',[$student->id])->withSuccess('Child Verified');
        }
        return redirect()->route('dashboard')->withError('Record not found');
    }


    public function verified($studentId)
    {
        return view('application.confirm',['student'=>Student::find($studentId)]);
    }

    public function linkChild(Request $request, $studentId)
    {
        $request->validate([
            'name'=>'required',
            'date_of_birth'=>'required',
            'gender'=>'required',
            'lga'=>'required',
            'student_type'=>'required',
            ]);
        $student = Student::find($studentId);
        
        $student->update([
            'guardian_id'=> Auth::user()->guardian->id,
            'date_of_birth'=> $request->date_of_birth,
            'gender_id'=> $request->gender,
            'lga_id'=> $request->lga,
            'student_type_id'=> $request->student_type,
            'name'=> $request->name,
            ]);
            event(new ChildLinked($student));
            
        return redirect()->route('dashboard')->withSuccess('The link was successful continue to the payment');
    }
    public function dashboard()
    {
        return view('dashboard');
    }
}
