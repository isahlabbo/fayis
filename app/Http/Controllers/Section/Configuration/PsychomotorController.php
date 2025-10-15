<?php

namespace App\Http\Controllers\Section\Configuration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Psychomotor;

class PsychomotorController extends Controller
{
    public function register(Request $request)
    {
        $request->validate(['name'=>'required|string']);
        $psycho = Psychomotor::where('name',$request->name)->first();
        if($psycho){
            return redirect()->route('dashboard.section.configuration.reportcard.index')->withWarning($request->name.' Exist');
        }else{
            Psychomotor::create(['name'=>$request->name]);
            return redirect()->route('dashboard.section.configuration.reportcard.index')->withSuccess('Psychomotor Registered');
        }
    }

    public function update(Request $request, $psychomotorId)
    {
        $request->validate(['name'=>'required|string']);

        $psycho = Psychomotor::find($psychomotorId);
        $psycho->update(['name'=>$request->name]);
        return redirect()->route('dashboard.section.configuration.reportcard.index')->withSuccess('Psychomotor Updated');
       
    }

    public function delete($psychomotorId)
    {
        $psycho = Psychomotor::find($psychomotorId);
        $psycho->update(['status'=>0]);
        return redirect()->route('dashboard.section.configuration.reportcard.index')->withSuccess('Psychomotor Deleted');
       
    }
}
