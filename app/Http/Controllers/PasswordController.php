<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    public function showUpdateForm()
    {
        return view('auth.passwords.update');
    }

    public function updatePassword(Request $request)
    {
        
        $request->validate([
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required',
        ]);

        $user = $request->user();
        

        if (Hash::make($request->password) == $user->password) {
            return back()->withErrors(['password' => 'The new password is the same as the current password.']);
        }

        $user->password = Hash::make($request->password_confirmation);
        $user->save();

        return redirect()->route('dashboard')->with('success', 'Password updated successfully.');
    }
}
