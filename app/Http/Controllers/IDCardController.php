<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CardRequest;
use Illuminate\Support\Facades\Hash;

class IDCardController extends Controller
{
    public function index() {
        return view('administration.card.index');
    }

    public function approve($requestId) {
        $request = CardRequest::find($requestId);

        $request->update(['status'=>'Approved']);

        return redirect()->route('administration.card.index')->withSuccess('Card Request Approved');
    }

    public function delete($requestId) {
        $request = CardRequest::find($requestId);

        $request->delete();

        return redirect()->route('administration.card.index')->withSuccess('Card Request Deleted');
    }

    public function update(Request $request, $requestId) {
        
        $request->validate([
            'section'=>'required',
            'position'=>'required',
            'reason'=>'required',
            'staffID'=>'required',
        ]);
        
        $cardRequest = CardRequest::find($requestId);

        $cardRequest->update([
            'section_id'=>$request->section,
            'position'=>$request->position,
            'reason'=>$request->reason,
            'staffID'=>$request->staffID,
        ]);

        return redirect()->route('administration.card.index')->withSuccess('Card Request Updated');
    }

    public function register(Request $request) {
        
        $request->validate([
            'name'=>'required',
            'email'=>'required',
            'section'=>'required',
            'position'=>'required',
            'reason'=>'required',
            'staffID'=>'required',
        ]);
        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make('123456'),
            'role'=>'staff',
        ]);
        $user->cardRequests()->create([
            'section_id'=>$request->section,
            'position'=>$request->position,
            'reason'=>$request->reason,
            'staffID'=>$request->staffID,
            'status'=> 'Pending'
        ]);

        return redirect()->route('administration.card.index')->withSuccess('Card Request Registered');
    }

}
