<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}

// public function store(Request $request)
// {
//     $validator = Validator::make($request->all(), [
//         'title' => 'required|min:3',
//         'body' => 'required|min:3'
//     ]);

//     if ($validator->fails()) {
//         return back()->with('toast_error', $validator->messages()->all()[0])->withInput();
//     }
//     $task = Task::create($request->all());
//     return redirect('tasks')->with('success', 'Task Created Successfully!');
//     // OR
//     return redirect('tasks')->withSuccess('Task Created Successfully!');
// }
