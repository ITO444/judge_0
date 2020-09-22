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
        $boxId = $data["boxId"];
        $language = $data["language"];
        $userDir = "/usercode/$userId";
        $boxHereS = "/run/$boxId";

        $user = User::find($userId);
        $user->runner_status = 'Compiling';
        $user->save();
        Storage::delete("/usercode/$userId/program.exe");
        Storage::put("/usercode/$userId/output.txt", '');

        Storage::delete(Storage::allFiles($boxHereS));
        Storage::copy("$userDir/program.$language", "$boxHereS/program.$language");

        $compileData = Run::compile($boxId, 10, 262144, $language);
        
        $error = $compileData['error'];
        Storage::put("$userDir/output.txt", "Box: $boxId\nCompile:\n$error\n");

        $compile = intval($compileData['exitcode']);
        if($compile){
            $user->runner_status = 'Compilation Error';
            $user->save();
            return;
        }
        if($language == "cpp"){
            Storage::copy("$boxHereS/program.exe", "$userDir/program.exe");
        }

        $user->runner_status = 'Running';
        $user->save();

        if($language == 'py'){$ext = 'py';}else{$ext = 'exe';}
        Storage::delete(Storage::allFiles($boxHereS));
        Storage::copy("$userDir/program.$ext", "$boxHereS/program.$ext");
        Storage::copy("$userDir/input.txt", "$boxHereS/input.txt");

        $executeData = Run::execute($boxId, 2, 262144, 1024, $language);

        $error = var_export($executeData, True);//$executeData['error'];
        $output = Storage::get("$boxHereS/output.txt");
        Storage::append("$userDir/output.txt", "Execute:\n$error\nOutput:\n$output");

        $execute = 1;//intval($executeData['exitcode']);
        if(isset($executeData['status'])){
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
     * vendor\vladimir-yuldashev\laravel-queue-rabbitmq\src\Consumer.php
     * vendor\vladimir-yuldashev\laravel-queue-rabbitmq\src\Queue\Jobs\RabbitMQJob.php
     * @return $this
     */
    public function setBoxId($boxId)
    {
        $this->data["boxId"] = $boxId;
        return $this;
    }
}
