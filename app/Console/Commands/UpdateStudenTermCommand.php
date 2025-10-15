<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Section;
use App\Models\SectionClass;

class UpdateStudenTermCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:student-terms';

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

        $bar->setBarWidth(100);

        $bar->start();

        foreach(Section::cursor() as $section){
            foreach($section->sectionClasses as $sectionClass){
                $sectionClass->updateAllStudentTerm();
                $bar->advance();
            } 
            
        }
        $bar->finish();
    }
}
