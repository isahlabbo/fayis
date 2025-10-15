<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Student;
use App\Models\Psychomotor;
use App\Models\AffectiveTrait;

class MigrateStudentAccessments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'school:student-accessment-migrate';

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
        $this->output->progressStart(count(Student::all()));
        foreach(Student::all() as $student){
            foreach($student->sectionclassStudents as $sectionClassStudent){
                foreach ($sectionClassStudent->sectionClassStudentTerms as $sectionClassStudentTerm) {
                    if($sectionClassStudentTerm->sectionClassStudentTermAccessment){
                        $affectiveTraits = [
                            ['name' => 'Punctuality', 'value' => $sectionClassStudentTerm->sectionClassStudentTermAccessment->punctuality ?? rand(2,5)],
                            ['name' => 'Attendance', 'value' => $sectionClassStudentTerm->sectionClassStudentTermAccessment->Attendance ?? rand(2,5)],
                            ['name' => 'Reliability', 'value' => $sectionClassStudentTerm->sectionClassStudentTermAccessment->reliability ?? rand(2,5)],
                            ['name' => 'Neatness', 'value' => $sectionClassStudentTerm->sectionClassStudentTermAccessment->neatness ?? rand(2,5)],
                            ['name' => 'Politeness', 'value' => $sectionClassStudentTerm->sectionClassStudentTermAccessment->politeness ?? rand(2,5)],
                            ['name' => 'Honesty', 'value' => $sectionClassStudentTerm->sectionClassStudentTermAccessment->honesty ?? rand(2,5)],
                            ['name' => 'Relationship With Pupils', 'value' => $sectionClassStudentTerm->sectionClassStudentTermAccessment->relationship_with_pupils ?? rand(2,5)],
                            ['name' => 'Self-Control', 'value' => $sectionClassStudentTerm->sectionClassStudentTermAccessment->self_control ?? rand(2,5)],
                            ['name' => 'Attentiveness', 'value' => $sectionClassStudentTerm->sectionClassStudentTermAccessment->attentiveness ?? rand(2,5)],
                            ['name' => 'Perseverance', 'value' => $sectionClassStudentTerm->sectionClassStudentTermAccessment->perseverance ?? rand(2,5)]
                        ];

                        foreach($affectiveTraits as $affectiveTrait){
                            
                            $sectionClassStudentTerm->sectionClassStudentTermAccessment->sectionClassStudentTermAccessmentAffectiveTraits()->firstOrCreate([
                            'affective_trait_id'=>AffectiveTrait::where('name',$affectiveTrait['name'])->first()->id,
                            'value'=>$affectiveTrait['value']]);
                            
                        }

                        $psychomotors = [
                            ['name' => 'Handwriting', 'value' => $sectionClassStudentTerm->sectionClassStudentTermAccessment->handwriting ?? rand(2,5)],
                            ['name' => 'Games', 'value' => $sectionClassStudentTerm->sectionClassStudentTermAccessment->games ?? rand(2,5)],
                            ['name' => 'Sports', 'value' => $sectionClassStudentTerm->sectionClassStudentTermAccessment->sports ?? rand(2,5)],
                            ['name' => 'Crafts', 'value' => $sectionClassStudentTerm->sectionClassStudentTermAccessment->crafts ?? rand(2,5)],
                            ['name' => 'Drawing & Painting', 'value' => $sectionClassStudentTerm->sectionClassStudentTermAccessment->drawing_and_painting ?? rand(2,5)]
                        ];
                        
                        foreach($psychomotors as $psychomotor){
                            
                            $sectionClassStudentTerm->sectionClassStudentTermAccessment->sectionClassStudentTermAccessmentPsychomotors()->firstOrCreate([
                            'psychomotor_id'=>Psychomotor::where('name',$psychomotor['name'])->first()->id,
                            'value'=>$psychomotor['value']]);
                            
                        }
                    }
                }
            }
            $this->output->progressAdvance();
        }
        $this->output->progressFinish();
    }

    
}
