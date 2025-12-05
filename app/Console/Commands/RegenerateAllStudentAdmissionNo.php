<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SectionClassStudent;

class RegenerateAllStudentAdmissionNo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'student:adminssion-no-regenerate';

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
        foreach(\App\Models\SectionClassStudent::where('status','Active')->get() as $studentClassStudent){
            $sectionClass = $studentClassStudent->sectionClass;
            $student = $studentClassStudent->student;
            $admissionNo = $sectionClass->generateAdmissionNo();

            if($admissionNo){
                $student->admission_no = $admissionNo;
                $student->save();
            }
            
        }
    }
}
