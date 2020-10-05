<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Helpers\Run;
use App\Submission;
use App\Test;
use App\Run as ARun;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;

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
        }else{
            $submission->result = 'Running';
            $submission->compiler_warning = $compileData['error'];
            $submission->save();
        }
        $dir = "/judging/$submission->id";
        Storage::makeDirectory($dir);
        if($language == "cpp"){
            Storage::copy("$boxHereS/program.exe", "$dir/program.exe");
        }else{
            Storage::put("$dir/program.py", $submission->source_code);
        }
        $tests = $submission->task->tests;
        $batchArr = $tests->map(function (Test $test) use ($submission) {
            $run = new ARun;
            $run->submission_id = $submission->id;
            $run->test_id = $test->id;
            $run->result = '';
            $run->runtime = 0;
            $run->memory = 0;
            $run->score = 0;
            $run->grader_feedback = '';
            $run->save();
            return new ProcessTest($run->id);
        });
        $batch = Bus::batch($batchArr)->then(function (Batch $batch) use ($submission) {
            $runs = $submission->runs;
            $count = $runs->count();
            if($count == $runs->where('result', 'Accepted')->count()){
                $submission->result = 'Accepted';
                $submission->score = 100000;
            }else{
                $submission->result = $submission->runs->avg('score');
                if($runs->where('result', 'Failed')->exists()){
                    $submission->result = 'Failed';
                }else if($runs->where('result', 'Wrong Answer')->exists()){
                    $submission->result = 'Wrong Answer';
                }else if($runs->where('result', 'Time Limit Exceeded')->exists()){
                    $submission->result = 'Time Limit Exceeded';
                }else if($runs->where('result', 'Runtime Error')->exists()){
                    $submission->result = 'Runtime Error';
                }
            }
        })->allowFailures()->onQueue('code')->dispatch();
    }

    /**
     * Set boxId
     * Edited files:
     * vendor\vladimir-yuldashev\laravel-queue-rabbitmq\src\Console\ConsumeCommand.php
     * vendor\vladimir-yuldashev\laravel-queue-rabbitmq\src\Consumer.php
     * vendor\vladimir-yuldashev\laravel-queue-rabbitmq\src\Queue\Jobs\RabbitMQJob.php
     * @return $this
     */
    public function setBoxId($boxId)
    {
        $this->boxId = $boxId;
        return $this;
    }
}
