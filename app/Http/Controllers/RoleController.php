<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        return view('configuration.access-control', ['activeTab' => 'roles']);
    }

    public function permissions()
    {
        return view('configuration.access-control', ['activeTab' => 'role-permissions']);
    }
}
