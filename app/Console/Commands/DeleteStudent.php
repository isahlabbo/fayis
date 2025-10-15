<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Student;

class DeleteStudent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'school:dummy-student-clean';

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
        foreach (Student::all() as $student) {
            if($student->name == 'student test name'){
                foreach ($student->sectionClassStudents as $class) {
                    foreach($class->sectionClassStudentTerms as $term){
                        $term->delete();
                    }
                    $class->delete();
                }
                $student->delete();
            }
            
            $this->output->progressAdvance();
            
        }
        $this->output->progressFinish();
    }
}
