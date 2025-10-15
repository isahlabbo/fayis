<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Section;
use App\Models\SectionClassStudent;

class UpdateStudentTerm
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $sessionTerm = Section::find(1)->currentSessionTerm();
        $sectionClassStudent = SectionClassStudent::where('status','Active')->first();

        if($sessionTerm->end_at == null){
           return redirect()->route('dashboard.session.configure',[$sessionTerm->currentSession()->id])->withWarning('Please configure the Academic session');
        }else if(strtotime($sessionTerm->end_at) <= time()){
            
            if($sessionTerm->term_id < 3){
                //   go to the next term resume confirmation
                return redirect()->route('dashboard.student.resume',[$sessionTerm->id])->withWarning('Please Confirmed The School Resumption');
            }else{
                // handle the student promotion and student graduation
            }
            
        }

        return $next($request);
    }

    
}
