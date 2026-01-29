<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.user.index', ['type' => $request->segment(3)]);
    }

    public function update(Request $request, $userId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        $user = \App\Models\User::findOrFail($userId);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->has('password') && !empty($request->password)) {
            $request->validate([
                'password' => 'required|string|min:8|confirmed',
            ]);
            $user->update([
                'password' => bcrypt($request->password),
            ]);
        }
        if ($request->has('status')) {
            $user->update([
                'status' => $request->status,
            ]);
        }
        if ($request->has('role')) {
            $user->update([
                'role' => $request->role,
            ]);
        }
        return redirect()->back();
    }
}
