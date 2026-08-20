<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        return view('configuration.access-control', ['activeTab' => 'permissions']);
    }

    public function users()
    {
        return view('configuration.access-control', ['activeTab' => 'user-access']);
    }
}
