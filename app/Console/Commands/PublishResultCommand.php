<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SectionClass;

class PublishResultCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'result:publish';

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
        $bar = $this->output->createProgressBar(count(SectionClass::all()));

        $bar->start();

        foreach(SectionClass::all() as $sectionClass){
            foreach($sectionClass->sectionClassStudents->where('status', 'Active') as $studentInClass){
                foreach($studentInClass->sectionClassStudentTerms->where('academic_session_term_id', $sectionClass->currentSessionTerm()->id) as $studentTerm){
                    $publish = $studentTerm->sectionClassStudentTermResultPublish()->firstOrCreate();
                    $publish->updatePublishRecord();
                    $studentTerm->publishUpload();
                }
            }
            $bar->advance();
        }

        $bar->finish();
    }
}
