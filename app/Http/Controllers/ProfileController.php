<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfileController extends Controller
{
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
            'password'=>'nullable|string|min:8',
            'picture'=>'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $user = User::findOrFail($userId);
        $user->name = $request->name;
        $user->email = $request->email;

        if($request->password){
            $user->password = bcrypt($request->password);
        }

        // Handle picture upload if provided
        if($request->hasFile('picture')){
            $file = $request->file('picture');
            $filename = time().'_'.$file->getClientOriginalName();
            $filePath = $file->storeAs('uploads/profile_pictures', $filename, 'public');
            $user->profile_photo_path = '/storage/'.$filePath;
        }

        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully.');    
        
    }

    public function cardRequest(Request $request, $userId){
        $request->validate([
            'section'=>'required',
            'position'=>'required',
            'reason'=>'required|string|max:1000',
            'staffID'=>'nullable|string|max:255',
        ]);

        $user = User::findOrFail($userId);

        $user->cardRequests()->create([
            'section_id'=>$request->section,
            'position'=>$request->position,
            'reason'=>$request->reason,
            'staffID'=>$request->staffID,
        ]);

        return redirect()->back()->with('success', 'ID Card request submitted successfully.');
        
    }
}
