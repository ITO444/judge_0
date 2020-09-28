<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Submission;
use App\Run as ARun;
use App\Helpers\Run;
use Illuminate\Support\Facades\Storage;

class ProcessTest implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $boxId;
    protected $runId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($runId)
    {
        $this->runId = $runId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $boxId = $this->boxId;
        $run = ARun::find($this->runId);
        $submission = $run->submission;
        $language = $submission->language;
        $task = $submission->task;
        $test = $run->test;
        $boxHereS = "/run/$boxId";

        if($language == 'py'){$ext = 'py';}else{$ext = 'exe';}
        Storage::delete(Storage::allFiles($boxHereS));
        Storage::copy("/judging/$submission->id/program.$ext", "$boxHereS/program.$ext");
        Storage::copy("tests/$test->id.in", "$boxHereS/input.txt");

        $run->result = 'Running';
        $run->save();
        $executeData = Run::execute($boxId, $task->runtime_limit, $task->memory_limit, 65536, $language);

        if(isset($executeData['status'])){
            $run->result = 'Runtime Error';
            $run->grader_feedback = $executeData['error'];
            $submission->save();
            return;
        }
    }

    /**
     * Set boxId
     * Edited files:
     * vendor\vladimir-yuldashev\laravel-queue-rabbitmq\src\Console\ConsumeCommand.php
     * @return $this
     */
    public function setBoxId($boxId)
    {
        $this->boxId = $boxId;
        return $this;
    }
}
