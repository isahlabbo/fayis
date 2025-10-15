<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SubjectTeacherTermlyUpload;

class ComputeSubjectResultUploadAverage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'compute:subject-uploade-average';

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
        foreach (SubjectTeacherTermlyUpload::all() as $uploade) {
            $uploade->computeAndSaveUploadAverage();
            $this->output->progressAdvance();
        }
        $this->output->progressFinish();
    }
}
