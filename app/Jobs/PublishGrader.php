<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Task;
use App\Helpers\Run;
use Illuminate\Support\Facades\Storage;
use App\Events\UpdatePublish;

class PublishGrader implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $boxId;
    protected $taskId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($taskId)
    {
        $this->taskId = $taskId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $boxId = $this->boxId;
        $task = Task::find($this->taskId);
        $boxHereS = "/run/$boxId";

        Storage::delete(Storage::allFiles($boxHereS));
        Storage::put("$boxHereS/program.cpp", $task->grader);
        Storage::copy("/testlib/testlib.h", "$boxHereS/testlib.h");

        $task->grader_status = 'Compiling';
        $task->save();
        event(new UpdatePublish($task->id, 'Compiling', '<div class="alert alert-info">Compiling (This may take a while)</div>'));
        $compileData = Run::compile($boxId, 30, 262144, 'cpp');

        if(isset($compileData['status'])){
            $task->grader_status = 'Compilation Error';
            $task->save();
            event(new UpdatePublish($task->id, 'Compilation Error', '<div class="alert alert-danger">'.$compileData['error'].'</div>'));
            return;
        }
        Storage::delete("/graders/$task->id.exe");
        Storage::move("$boxHereS/program.exe", "/graders/$task->id.exe");
        $task->published = 1;
        $task->grader_status = 'Accepted';
        $task->save();
        event(new UpdatePublish($task->id, 'Published', '<div class="alert alert-success">Done</div>'));
    }

    /**
     * Handle a job failure.
     *
     * @param  \Throwable  $exception
     * @return void
     */
    public function failed(Throwable $exception)
    {
        $task = Task::find($this->taskId);
        $task->grader_status = 'Failed';
        $task->save();
        event(new UpdatePublish($task->id, 'Failed', ':('));
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
