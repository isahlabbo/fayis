<?php

namespace App\Http\Controllers\Section\Configuration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AffectiveTrait;

class AffectiveTraitController extends Controller
{
    public function register(Request $request)
    {
        $request->validate(['name'=>'required|string']);
        $trait = AffectiveTrait::where('name',$request->name)->first();
        if($trait){
            return redirect()->route('dashboard.section.configuration.reportcard.index')->withWarning($request->name.' Exist');
        }else{
            AffectiveTrait::create(['name'=>$request->name]);
            return redirect()->route('dashboard.section.configuration.reportcard.index')->withSuccess('Affective Trait Registered');
        }
    }

    public function update(Request $request, $affectiveTraitId)
    {
        $request->validate(['name'=>'required|string']);

        $trait = AffectiveTrait::find($affectiveTraitId);
        $trait->update(['name'=>$request->name]);
        return redirect()->route('dashboard.section.configuration.reportcard.index')->withSuccess('Affective Trait Updated');
       
    }

    public function delete($affectiveTraitId)
    {
        $psycho = AffectiveTrait::find($affectiveTraitId);
        $psycho->update(['status'=>0]);
        return redirect()->route('dashboard.section.configuration.reportcard.index')->withSuccess('Affective Trait Deleted');
    }
}
