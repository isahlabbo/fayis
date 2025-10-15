<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Guardian;
use App\Models\Section;

class GenerateStudents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'school:student-generate';

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
        if(config('app.mode') == "TEST"){
            $this->output->progressStart(count(Section::all()));
            foreach (Section::all() as $section) {
                foreach($section->sectionClasses as $sectionClass){
                    $number = '08162460000';
                for($i = 1; $i <= 40; $i++){

                        $guardian = Guardian::create([
                            'name'=>'guardian test name '.$i,
                            'address'=>'guardian address '. $i,
                            'phone'=>$number+$i,
                            'email'=>$number+$i.'@'.str_replace(' ','',strtolower(config('app.name'))).'.com'
                        ]);

                        $student = $guardian->students()->create([
                            'name'=>"student test name",
                            'admission_no'=>$sectionClass->generateAdmissionNo(),
                            'academic_session_id'=>$sectionClass->classAdmissionSession()->id,
                            'date_of_birth'=>'2020/12/12',
                            'section_class_id'=>$sectionClass->id,
                            'gender_id'=>rand(1,2)
                        ]);
                        $classStudent = $student->sectionClassStudents()->create(['section_class_id'=>$sectionClass->id]);
                        $count = 1;
                        foreach($student->currentSession()->academicSessionTerms as $academicSessionTerm){
                            if($count == 1){
                                $academicSessionTerm->sectionClassStudentTerms()->create(['status'=>'Active','section_class_student_id'=>$classStudent->id]);
                            }else{
                                $academicSessionTerm->sectionClassStudentTerms()->create(['section_class_student_id'=>$classStudent->id]);
                            }
                            $count++;
                        }
                        $number++;
                        
                    }
                }
                $this->output->progressAdvance();
            }
            $this->output->progressFinish();
        }else {
            dd ("Sorry we cant execute this command because your application is not in test mode");
        }
    }
   
}
