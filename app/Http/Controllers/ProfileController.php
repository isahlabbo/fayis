<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Qualification;
use App\Services\Upload\FileUpload;

class ProfileController extends Controller
{
    use FileUpload;

    public function show(){
        return view('profile.show');
    }

    public function card($userId){
        return view('profile.card',['user'=>User::findOrFail($userId)]);
    }

    function update(Request $request, $userId){
       
        $request->validate([
            'name'=>'required|string|max:255',
            'email'=>'required|email|max:255',
            'picture'=>'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
        ]);
        $user = User::findOrFail($userId);
        $user->update([
            'name'=> $request->name,
            'email'=> $request->email,
        ]);
        if($request->password){
            $request->validate([
                'password'=>'required|min:6',
            ]);
            $user->password = bcrypt($request->password);
            $user->save();
        }

        if($user->teacher){
           
            $user->teacher->update([
                "lga_id" => $request->lga,
                "marital_status" => $request->marital_status,
                "phone" => $request->phone,
                "date_of_birth" => $request->date_of_birth,
                "address" => $request->address,
                "date_of_appointment" => $request->date_of_appointment,
                "appointment_grade_level" => $request->appointment_grade_level,
                "present_grade_level" => $request->present_grade_level,
                "trcn" => $request->trcn
            ]);
        }

        // Handle picture upload if provided
        if($request->hasFile('picture')){
            $file = $request->file('picture');
            $location = 'profile/staff/';
            if($user->profile_photo_path){
                $this->storeFile($user, 'profile_photo_path', $file, $location);
            }else{
                $this->updateFile($user, 'profile_photo_path', $file, $location);
            }
        }

        return redirect()->back()->with('success', 'Profile updated successfully.');    
        
    }

    public function cardRequest(Request $request, $userId){
        $request->validate([
            'section'=>'required',
            'position'=>'required',
            'reason'=>'required|string|max:1000',
            'signature'=>'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
            
        ]);

        $user = User::findOrFail($userId);

        $cardRequest = $user->cardRequests()->create([
            'section_id'=>$request->section,
            'position'=>$request->position,
            'reason'=>$request->reason,
            'staffID'=>'FAYIS/TS/'.str_pad($user->id, 3, '0', STR_PAD_LEFT),
        ]);

        if($request->hasFile('signature')){
            $file = $request->file('signature');
            $location = 'profile/signatures/';
            $this->storeFile($cardRequest, 'signature', $file, $location);
        }


        return redirect()->back()->with('success', 'ID Card request submitted successfully.');
        
    }

    public function addQualification(Request $request, $userId){
        $request->validate([
            'type'=>'required',
            'file'=>'required',
            
        ]);
        $user = User::find($userId);
        $qualification = $user->teacher->qualifications()->create([
            'name'=>$request->type,
        ]);

        $file = $request->file('file');
        $location = 'profile/qualifications/';
        $this->storeFile($qualification, 'file', $file, $location);
        

        return redirect()->back()->with('success', 'Qualification Added.');
        
    }

    public function deleteQualification(Request $request, $qualificationId){
        
        $qualification = Qualification::find($qualificationId);
        
        $this->deleteFile($qualification, 'file');
    

        $qualification->delete();

        return redirect()->back()->with('success', 'Qualification Deleted.');
        
    }
}
