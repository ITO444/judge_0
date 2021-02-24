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
use Throwable;

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
        $executeData = Run::execute($boxId, $task->runtime_limit / 1000, $task->memory_limit, 65536, $language);
        $run->runtime = min($task->runtime_limit, $executeData['time'] * 1000);
        $run->memory = min($task->memory_limit, $executeData['cg-mem']);
        $run->grader_feedback = '('.$executeData['time'].' sec real, '.$executeData['time-wall'].' sec wall, '.$executeData['cg-mem']." KB mem)\n";
        
        if($run->runtime == $task->runtime_limit || (isset($executeData['status']) && $executeData['status'] == 'TO')){
            $run->result = 'Time Limit Exceeded';
            $run->grader_feedback .= "Time limit exceeded\n";
            $run->save();
            return;
        }
        if($run->memory >= $task->memory_limit || isset($executeData['status'])){
            $run->result = 'Runtime Error';
            $run->grader_feedback .= isset($executeData['status']) ? $executeData['error'] : "Memory limit exceeded\n";
            $run->save();
            return;
        }
        $run->save();

        Storage::copy("tests/$test->id.in", "$boxHereS/input.txt");
        Storage::copy("tests/$test->id.out", "$boxHereS/answer.txt");
        if($task->grader_status == 'Accepted'){
            Storage::copy("/graders/$task->id.exe", "$boxHereS/grader.exe");
        }else{
            Storage::copy("/graders/wcmp.exe", "$boxHereS/grader.exe");
        }
        $gradeData = Run::grade($boxId);
        if(!isset($gradeData['exitcode'])){
            $run->result = 'Failed';
            $run->grader_feedback .= "Failed:\n".$gradeData['error'];
            $run->save();
            return;
        }
        $exitCode = intval($gradeData['exitcode']);
        $run->grader_feedback .= Storage::get("$boxHereS/result.txt");
        if($exitCode === 0){
            $run->result = 'Accepted';
            $run->score = 100000;
            $run->save();
            return;
        }else{
            $run->result = 'Wrong Answer';
            $run->save();
            return;
        }
    }

    /**
     * Handle a job failure.
     *
     * @param  \Throwable  $exception
     * @return void
     */
    public function failed(Throwable $exception)
    {
        $run = ARun::find($this->runId);
        $run->result = '';
        $run->grader_feedback .= $exception->getMessage();
        $run->save();
        return;
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
