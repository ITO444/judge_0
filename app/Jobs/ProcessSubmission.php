<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessSubmission implements ShouldQueue
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
        $f = fopen(env('APP_PATH').'resources/testing/queue.txt', 'a');
        fwrite($f, 'hi '.$data['in'].' box '.$data['boxId'].'\n');
        sleep($data['sleep']);
        fwrite($f, 'bye '.$data['in'].' sleep '.$data['sleep'].'\n');
        fclose($f);
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
