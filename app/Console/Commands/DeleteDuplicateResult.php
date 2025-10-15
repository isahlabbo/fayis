<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SubjectTeacherTermlyUpload;

class DeleteDuplicateResult extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:duplicate-results';

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
        $this->output->progressStart(count(SubjectTeacherTermlyUpload::all()));
        foreach (SubjectTeacherTermlyUpload::all() as $upload) {
            if(count($upload->studentResults) > count($upload->sectionClassSubjectTeacher->sectionClassSubject->sectionClass->activeStudentIds())){
                foreach($upload->studentResults as $result){
                    if(!in_array($result->sectionClassStudentTerm->sectionClassStudent->id,
                    $upload->sectionClassSubjectTeacher->sectionClassSubject->sectionClass->activeStudentIds())){
                        $result->delete();
                    }else{
                        $subject = null;
                        foreach($upload->studentResults as $studentResult){
                            $currentSubject = $studentResult->subjectTeacherTermlyUpload->sectionClassSubjectTeacher->sectionClassSubject->subject->name;
                            if($subject == $currentSubject){
                                $studentResult->delete();
                            }else{
                                $subject = $currentSubject;
                            }
                        }  
                    }
                }
            }
            $this->output->progressAdvance();
        }
        $this->output->progressFinish();
    }
}
