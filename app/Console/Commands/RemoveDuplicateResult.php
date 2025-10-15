<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SectionClassStudentTerm;

class RemoveDuplicateResult extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'school:remove-duplicate-result';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->output->progressStart(count(SectionClassStudentTerm::where('status','Active')));
        foreach (SectionClassStudentTerm::where('status','Active') as $sectionClassStudentTerm) {
                $subjectIds = [];
                foreach($sectionClassStudentTerm->studentResults as $studentResult){
                    if(in_array($studentResult->subjectTeacherTermlyUpload->sectionClassSubjectTeacher->sectionClassSubject->subject->id,$subjectIds)){
                        $studentResult->delete();
                    }else{
                        $subjectIds[] = $studentResult->subjectTeacherTermlyUpload->sectionClassSubjectTeacher->sectionClassSubject->subject->id;
                    }
                    $this->output->progressAdvance();
                }
           
        }
        $this->output->progressFinish();
    }
}
