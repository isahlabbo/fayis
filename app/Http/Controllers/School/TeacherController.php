<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\User;
use App\Actions\Fortify\PasswordValidationRules;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TeacherController extends Controller
{
    use PasswordValidationRules;

    public function index()
    {
       return view('school.teacher.index',['teachers'=>Teacher::all()]);
    }

    public function create()
    {
       return view('school.teacher.create');
    }

    public function register(Request $request)
    {
      $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:14', 'unique:teachers'],
            'address' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['required'],
            'password' => $this->passwordRules(),
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->teacher()->create([
            'address'=>$request->address,
            'phone'=>$request->phone,
            'date_of_birth'=>$request->date_of_birth,
        ]);

        return redirect()->route('dashboard.teacher.index')->withSuccess('Teacher Registered Successfully');
    }

    public function update(Request $request, $teacherId)
    {
        $rules = [];
        $teacher = Teacher::find($teacherId);
        if($teacher->user->name != $request->name){
            $rules['name'] = 'required|string|max:255';
        }
        if($teacher->user->email != $request->email){
            $rules['email'] = 'required|string|email|max:255|unique:users';
        }
        if($teacher->phone != $request->phone){
            $rules['phone'] = 'required|string|max:14|unique:teachers';
        }
        if($teacher->address != $request->address){
            $rules['address'] = 'required|string|max:255';
        }
        if($teacher->user->date_of_birth != $request->date_of_birth){
            $rules['date_of_birth'] = 'required';
        }
        if($request->password){
            $rules['password'] = $this->passwordRules();
        }
        $request->validate($rules);
        

        $teacher->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if($request->password){
            $teacher->user->update(['password' => Hash::make($request->password)]);
        }

        $teacher->update([
            'address'=>$request->address,
            'phone'=>$request->phone,
            'date_of_birth'=>$request->date_of_birth,
        ]);

        return redirect()->route('dashboard.teacher.index')->withSuccess('Teacher Updated Successfully');
    }

    public function delete($teacherId)
    {
        $teacher = Teacher::find($teacherId);
        $teacher->user->delete();
        $teacher->delete();
        return redirect()->route('dashboard.teacher.index')->withSuccess('Teacher Deleted Successfully');
    }
}
