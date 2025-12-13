<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateResultAccessCode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'result:access-code-generate';

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
        $bar = $this->output->createProgressBar(count(\App\Models\SectionClassStudentTerm::all()));

        $bar->setBarWidth(100);

        $bar->start();

        foreach(\App\Models\SectionClassStudentTerm::all() as $sectionClassStudentTerm){
            if(!$sectionClassStudentTerm->access_code){
                // Generate unique access code
                $code = \Str::upper(\Str::random(8));

                // check uniqueness
                while(\App\Models\SectionClassStudentTerm::where('access_code',$code)->exists()){
                    $code = \Str::upper(\Str::random(8));
                }
                
                $sectionClassStudentTerm->access_code = $code;
                $sectionClassStudentTerm->save();
            }
            $bar->advance();
        }
        
        $bar->finish();
    }
}
