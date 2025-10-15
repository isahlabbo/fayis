<?php

namespace App\Http\Controllers\Section;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Section;
use App\Events\SectionCreated;

class SectionController extends Controller
{
    public function view($sectionId)
    {
        
        return view('section.view', ['section'=>Section::find($sectionId)]);
    }

    public function index()
    {
        return view('section.index', ['sections'=>Section::all()]);
    }

    public function classes($sectionId)
    {
        
        return view('section.classes', ['section'=>Section::find($sectionId)]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'=>'required|string',
            'level'=>'required',
        ]);
        $section = Section::where('name',$request->name)->first();

        if($section){
            return redirect()->route('dashboard.section.index')->withWarning('Section Exist');
        }else{
            $section = Section::create([
                'name'=>strtoupper($request->name),
                'level'=>$request->level,
                'duration'=>$request->duration,
                ]);
                event(new SectionCreated($section));

            return redirect()->route('dashboard.section.index')->withSuccess('Section Registered');
        }
    }
    public function update(Request $request, $sectionId)
    {
        $request->validate([
            'name'=>'required|string',
            'level'=>'required',
            'duration'=>'required'
            ]);

        $section = Section::find($sectionId);
        $section->update([
            'name'=>strtoupper($request->name),
            'duration'=>$request->duration,
        ]);

        if($section->level != $request->level){
            $section->update(['level'=>$request->level]);
        }

        if($section->duration != $request->duration){
            $section->update(['duration'=>$request->duration]);
        }
        
        return redirect()->route('dashboard.section.index',[$section->id])->withSuccess('Section Updated');
    }

    public function delete($sectionId){
        
        $section = Section::find($sectionId);
        $count = 0;
        foreach ($section->sectionClasses as $sectionClass) {
            $count++;
            foreach ($sectionClass->sectionClassStudents as $key => $value) {
               $count++;
            }
        }

        if($count > 0){
            return redirect()->route('dashboard.section.index')->withWarning('We cant delete this section there: there are '.$count.' record depend on it');
            
        }else{
            $section->delete();
            return redirect()->route('dashboard.section.index')->withSuccess('Section Deleted');
        }
    }
    
}
