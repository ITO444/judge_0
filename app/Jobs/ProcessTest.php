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
        $run->runtime = $executeData['time'];
        $run->memory = $executeData['cg-mem'];
        $run->save();
        if(isset($executeData['status'])){
            if($executeData['status'] == 'TO'){
                $run->result = 'Time Limit Exceeded';
                $run->grader_feedback = $executeData['error'];
                $submission->save();
                return;
            }
            $run->result = 'Runtime Error';
            $run->grader_feedback = $executeData['error'];
            $submission->save();
            return;
        }
        Storage::copy("tests/$test->id.in", "$boxHereS/input.txt");
        Storage::copy("tests/$test->id.out", "$boxHereS/answer.txt");
        if($task->grader_status != 'Compiled'){
            Storage::copy("graders/wcmp.exe", "$boxHereS/grader.exe");
        }
        $gradeData = Run::grade($boxId);
        if(!isset($gradeData['exitcode'])){
            $run->result = 'Failed';
            $run->grader_feedback = $executeData['error'];
            $submission->save();
            return;
        }
        $exitCode = intval($gradeData['exitcode']);
        if($exitCode === 0){
            $run->result = 'Accepted';
            $run->score = 100000;
            $run->grader_feedback = $executeData['error'];
            $submission->save();
            return;
        }else{
            $run->result = 'Wrong Answer';
            $run->grader_feedback = $executeData['error'];
            $submission->save();
            return;
        }
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
