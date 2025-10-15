<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SubjectTeacherTermlyUpload;

class UpdateUpload extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'school:upload-update';

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
            $upload->update(['academic_session_term_id'=>$upload->currentSessionTerm()->id]);
            $this->output->progressAdvance();
        }
        $this->output->progressFinish();

    }
}
