<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Helpers\Run;
use App\User;
use Illuminate\Support\Facades\Storage;

class ProcessRunner implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data = [];

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = $this->data;
        
        $userId = $data["userId"];
        $user = User::find($userId);
        $user->runner_status = 'Compiling';
        $user->save();
        Storage::put("/usercode/$userId/output.txt", '');

        $boxId = $data["boxId"];
        $language = $data["language"];
        $dir = "/usercode/$userId";
        $compile = Run::compile($boxId, $language, $dir);
        
        if($compile){
            $user->runner_status = 'Compilation Error';
            $user->save();
            return;
        }

        $user->runner_status = 'Running';
        $user->save();
        $execute = Run::execute($boxId, $language, $dir);
        if($execute){
            $user->runner_status = 'Runtime Error';
            $user->save();
            return;
        }
        $user->runner_status = 'Done';
        $user->save();
        return;
    }

    /**
     * Set boxId
     * Edited files:
     * vendor\vladimir-yuldashev\laravel-queue-rabbitmq\src\Console\ConsumeCommand.php
     * @return $this
     */
    public function setBoxId($boxId)
    {
        $this->data["boxId"] = $boxId;
        return $this;
    }
}
