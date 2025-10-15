<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StaffCode;
use Auth;

class StaffController extends Controller
{
    public function verify(Request $request)
    {
        $request->validate(['staff_code'=>'required']);

        if($code = StaffCode::where('code',$request->staff_code)->first()){
            if($code->status == 'used'){
                return redirect()->route('dashboard')->withWarning('This Code was already used by another Staff');
            }else{
                $code->staff()->create(['guardian_id'=>Auth::user()->guardian->id]);
                $code->update(['status'=>'used']);
                return redirect()->route('dashboard')->withSuccess('Staff Verified');
            }
        }else{
            return redirect()->route('dashboard')->withWarning('Invalid Staff Code');
        }
    }
}
