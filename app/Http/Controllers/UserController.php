<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        return view('administration.user.index');
    }

    public function register(Request $request)
    {
        
        $request->validate([
            'name'=>'required|string',
            'email'=>'required|email|unique:users',
            'role'=>'required',
            'password'=>'required|min:8',
            ]);
        User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'role'=>$request->role,
        ]);
        
        return redirect()->route('dashboard.user.index')->withSuccess($request->role.' User Account Created');
    }

    public function update(Request $request, $userId)
    {
        
        $request->validate([
            'name'=>'required|string',
            'email'=>'required|email',
            'role'=>'required',
            ]);
        $user = User::find($userId);
        $user->update([
            'name'=>$request->name,
            'email'=>$request->email,
            'role'=>$request->role,
        ]);

        if($request->password){
            $user->update(['password'=>Hash::make($request->password)]);
        }
        
        return redirect()->route('dashboard.user.index')->withSuccess($request->role.' User Account Created');
    }
}
