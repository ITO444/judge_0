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
use Throwable;

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
            $run->result = 'On Queue';
            $run->runtime = 0;
            $run->memory = 0;
            $run->score = 0;
            $run->grader_feedback = '';
            $run->save();
            return new ProcessTest($run->id);
        });
        $batch = Bus::batch($batchArr)->finally(function (Batch $batch) use ($submission) {
            $submission->refresh();
            $task = $submission->task;
            $runs = $submission->runs;
            $count = $runs->count();
            $participation = $submission->participation;
            if($count == $runs->where('result', 'Accepted')->count()){
                $submission->result = 'Accepted';
                $submission->score = 100000;
                $submission->save();
            }else{
                if($participation == null){
                    $submission->score = $submission->runs->avg('score');
                    if($runs->whereIn('result', ['Running', 'On Queue', 'Failed', ''])->first()){
                        $submission->result = 'Failed';
                    }else if($runs->where('result', 'Wrong Answer')->first()){
                        $submission->result = 'Wrong Answer';
                    }else if($runs->where('result', 'Time Limit Exceeded')->first()){
                        $submission->result = 'Time Limit Exceeded';
                    }else if($runs->where('result', 'Runtime Error')->first()){
                        $submission->result = 'Runtime Error';
                    }else{
                        $submission->result = '';
                    }
                }else{
                    $submission->result = $runs->where('result', '<>', 'Accepted')->first()->result;
                }
                $submission->save();
            }
            Storage::deleteDirectory("/judging/$submission->id");
            $user = $submission->user;
            if($participation !== null){
                $information = $participation->information;
                $contest = $participation->contest;
                $submissions = $task->submissions->where('participation_id', $participation->id);
                $firstAC = $submissions->where('result', 'Accepted')->first();
                if($contest->cumulative()){
                    $scores = $submission->subtaskScores();
                    $scores['score'] = 0;
                    if($firstAC != null){
                        $scores['solve_time'] = $firstAC->created_at;
                    }else{
                        $scores['solve_time'] = null;
                    }
                    foreach($submissions as $submission2){
                        $scores2 = $submission2->subtaskScores();
                        foreach($scores['subtasks'] as $subtask => $score){
                            $scores['subtasks'][$subtask] = max($scores['subtasks'][$subtask], $scores2['subtasks'][$subtask]);
                        }
                    }
                    foreach($scores['subtasks'] as $subtask => $score){
                        $scores['score'] += $scores['subtasks'][$subtask];
                    }
                    $information['tasks'][$task->id] = $scores;
                }else{
                    $information['tasks'][$task->id] = $submission->subtaskScores();
                    if($firstAC != null){
                        $information['tasks'][$task->id]['solve_time'] = $firstAC->created_at;
                    }else{
                        $information['tasks'][$task->id]['solve_time'] = null;
                    }
                }
                $participation->score = 0;
                foreach($information['tasks'] as $taskid => $scores){
                    $participation->score += $scores['score'];
                }
                $submission->score = $information['tasks'][$task->id]['score'];
                $submission->save();
                $participation->information = $information;
                $participation->save();
            }else{
                $task = $submission->task;
                $task->solved = $task->submissions->where('participation_id', null)->where('result', 'Accepted')->unique('user_id')->count();
                $task->save();
                $user->solved = $user->submissions->where('participation_id', null)->where('result', 'Accepted')->unique('task_id')->count();
                $user->save();
            }
        })->allowFailures()->onQueue('code')->dispatch();
    }

    /**
     * Handle a job failure.
     *
     * @param  \Throwable  $exception
     * @return void
     */
    public function failed(Throwable $exception)
    {
        $submission = Submission::find($this->submissionId);
        $submission->result = '';
        $submission->save();
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
