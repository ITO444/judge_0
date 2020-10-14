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
use App\Events\UpdateRunner;

class ProcessRunner implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $boxId;
    protected $userId;
    protected $language;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userId, $language)
    {
        $this->userId = $userId;
        $this->language = $language;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $userId = $this->userId;
        $boxId = $this->boxId;
        $language = $this->language;
        $userDir = "/usercode/$userId";
        $boxHereS = "/run/$boxId";
        $user = User::find($userId);
        
        Storage::delete("/usercode/$userId/program.exe");
        Storage::put("/usercode/$userId/output.txt", '');

        Storage::delete(Storage::allFiles($boxHereS));
        Storage::copy("$userDir/program.$language", "$boxHereS/program.$language");

        $user->runner_status = 'Compiling';
        $user->save();
        $compileData = Run::compile($boxId, 10, 262144, $language);
        
        $error = $compileData['error'];
        Storage::put("$userDir/output.txt", "Box: $boxId\nCompile:\n$error\n");

        $compile = 1;//intval($compileData['exitcode']);
        if(isset($compileData['status'])){
            $user->runner_status = '';
            $user->save();
            event(new UpdateRunner('Compilation Error', $userId));
            return;
        }
        if($language == "cpp"){
            Storage::copy("$boxHereS/program.exe", "$userDir/program.exe");
        }

        if($language == 'py'){$ext = 'py';}else{$ext = 'exe';}
        Storage::delete(Storage::allFiles($boxHereS));
        Storage::copy("$userDir/program.$ext", "$boxHereS/program.$ext");
        Storage::copy("$userDir/input.txt", "$boxHereS/input.txt");

        $user->runner_status = 'Running';
        $user->save();
        $executeData = Run::execute($boxId, 2, 262144, 1024, $language);

        $error = $executeData['error'];//var_export($executeData, True);
        $output = Storage::get("$boxHereS/output.txt");
        Storage::append("$userDir/output.txt", "Execute:\n$error\nOutput:\n$output");

        $execute = 1;//intval($executeData['exitcode']);
        if(isset($executeData['status'])){
            if($executeData['status'] == 'TO'){
                $user->runner_status = '';
                $user->save();
                event(new UpdateRunner('Time Limit Exeeded', $userId));
                return;
            }
            $user->runner_status = '';
            $user->save();
            event(new UpdateRunner('Runtime Error', $userId));
            return;
        }
        $user->runner_status = '';
        $user->save();
        event(new UpdateRunner('Done', $userId));
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
