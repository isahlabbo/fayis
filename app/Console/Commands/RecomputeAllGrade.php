<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\StudentResult;

class RecomputeAllGrade extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'result:re-compute';

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
        $this->output->progressStart(count(StudentResult::all()));
        foreach (StudentResult::all() as $result) {
            $result->reComputeGrade();
            $this->output->progressAdvance();
        }
        $this->output->progressFinish();
    }
}
