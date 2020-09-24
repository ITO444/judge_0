<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Helpers\Run;
use App\Submission;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;

class ProcessSubmission implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $boxId;
    protected $submissionId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($submissionId)
    {
        $this->submissionId = $submissionId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $boxId = $this->boxId;
        $submission = Submission::find($this->submissionId);
        $language = $submission->language;
        $task = $submission->task;
        $boxHereS = "/run/$boxId";

        Storage::delete(Storage::allFiles($boxHereS));
        Storage::put("$boxHereS/program.$language", $submission->source_code);

        $submission->result = 'Compiling';
        $submission->save();
        $compileData = Run::compile($boxId, $task->compile_time, 262144, $language);

        if(isset($compileData['status'])){
            $submission->result = 'Compilation Error';
            $submission->compiler_warning = $compileData['error'];
            $submission->save();
            return;
        }
        $dir = "/judging/$submission->id";
        Storage::makeDirectory($dir);
        if($language == "cpp"){
            Storage::copy("$boxHereS/program.exe", "$dir/program.exe");
        }else{
            Storage::put("$dir/program.py", $submission->source_code);
        }
        $tests = $submission->task->tests;
        $batchArr = $tests->map(function ($test) {
            return new ProcessTest($test->id);
        });
        $batch = Bus::batch($batchArr)->then(function (Batch $batch) {
            // dosomething
        })->allowFailures()->dispatch();
    }

    /**
     * Set boxId
     * Edited files:
     * vendor\vladimir-yuldashev\laravel-queue-rabbitmq\src\Console\ConsumeCommand.php
     * @return $this
     */
    public function setBoxId($boxId)
    {
        $this->$boxId = $boxId;
        return $this;
    }
}
