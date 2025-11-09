<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function index()
    {
        return view('administration.staff.index');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role'=>'staff'
        ]);

        return redirect()->route('administration.staff.index')->withSuccess('Staff Registered Successfully');
    }

    public function update(Request $request, $staffId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
        ]);

        $staff = User::find($staffId);
        $staff->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if($staff->password){
            $staff->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('administration.staff.index')->withSuccess('Staff Updated Successfully');
    }

    public function delete($staffId)
    {

        $staff = User::find($staffId);
        $staff->delete();

        return redirect()->route('administration.staff.index')->withSuccess('Staff Updated Successfully');
    }
}
