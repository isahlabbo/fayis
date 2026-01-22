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
            'status' => 'required|string',
            'role' => 'required|string',
        ]);

        $user = \App\Models\User::findOrFail($userId);
        $user->update($request->all());
        return redirect()->back();
    }
}
