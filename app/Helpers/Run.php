<?php

namespace App\Helpers;

use App\User;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class Run
{
    /**
     * Saves the user's code for runner
     *
     * @param string $language
     * @param string $code
     * @param string $input
     * @return void
     */
    public static function saveRunner($language, $code, $input)
    {
        $directory = '/usercode/'.auth()->user()->id;
        Storage::put("$directory/program.$language", $code);
        Storage::put("$directory/input.txt", $input);
    }

    /**
     * Saves the user's code for runner
     *
     * @param int $boxId
     * @param string $language
     * @param string $dir
     * @return int
     */
    public static function compile($boxId, $language, $dir)
    {
        $process = new Process(['isolate', '--cg', '-b', $boxId, '--cleanup']);
        $process->run();
        $process = new Process(['isolate', '--cg', '-b', $boxId, '--init']);
        $process->run();
        $boxHereS = "/run/$boxId";
        $boxHere = env('APP_PATH')."storage/app$boxHereS";
        $boxThere = rtrim($process->getOutput()).'/box';
        $dirFull = env('APP_PATH')."/storage/app".$dir;
        Storage::delete(Storage::allFiles($boxHereS));
        Storage::copy("$dir/program.$language", "$boxHereS/program.$language");
        $process = Process::fromShellCommandline("mv $boxHere/* $boxThere");
        $process->run();

        if($language == 'cpp'){
            putenv("PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin");
            $process = new Process([
                'isolate', '--cg', '-b', $boxId,
                '-t', '10', '-m', '262144', '-e', '-p',
                '-M', "$boxHere/compile.txt", '--run', '--',
                '/usr/bin/g++', '-static', '-Wno-unused-result',
                '-DONLINE_JUDGE', '-s', '-O2', '-o', 'program.exe', 'program.cpp'
            ]);
            $compile = $process->run();
            $error = $process->getErrorOutput();
            if($compile == 0){
                $process = new Process(['mv', "$boxThere/program.exe", "$dirFull"]);
                $process->run();
            }
        }else{
            $process = new Process([
                'isolate', '--cg', '-b', $boxId,
                '-t', '10', '-m', '262144', '-e', '-p',
                '-M', "$boxHere/compile.txt", '--run', '--',
                '/usr/bin/python3', '-S', '-m', 'py_compile', 'program.py'
            ]);
            $compile = $process->run();
            $error = $process->getErrorOutput();
        }
        Storage::put("$dir/output.txt", "Compile:\n$error\n");
        return $compile;
    }

    /**
     * Saves the user's code for runner
     *
     * @param int $boxId
     * @param string $language
     * @param string $dir
     * @return int
     */
    public static function execute($boxId, $language, $dir)
    {
        $process = new Process(['isolate', '--cg', '-b', $boxId, '--cleanup']);
        $process->run();
        $process = new Process(['isolate', '--cg', '-b', $boxId, '--init']);
        $process->run();
        $boxHereS = "/run/$boxId";
        $boxHere = env('APP_PATH')."storage/app$boxHereS";
        $boxThere = rtrim($process->getOutput()).'/box';
        $dirFull = env('APP_PATH')."/storage/app".$dir;
        if($language == 'py'){$ext = 'py';}else{$ext = 'exe';}
        Storage::delete(Storage::allFiles($boxHereS));
        Storage::copy("$dir/program.$ext", "$boxHereS/program.$ext");
        Storage::copy("$dir/input.txt", "$boxHereS/input.txt");
        $process = Process::fromShellCommandline("mv $boxHere/* $boxThere");
        $process->run();

        if($language == 'cpp'){
            $process = new Process([
                'isolate', '--cg', '-b', $boxId,
                '-t', '2', '-m', '262144', '-e', '-p',
                '-i', 'input.txt', '-o', 'output.txt',
                '-M', "$boxHere/compile.txt", '--run', '--', 'program.exe'
            ]);
            $execute = $process->run();
            $error = $process->getErrorOutput();
        }else{
            $process = new Process([
                'isolate', '--cg', '-b', $boxId,
                '-t', '2', '-m', '262144', '-e', '-p',
                '-i', 'input.txt', '-o', 'output.txt',
                '-M', "$boxHere/compile.txt", '--run', '--',
                '/usr/bin/python3', '-O', '-S', 'program.py'
            ]);
            $execute = $process->run();
            $error = $process->getErrorOutput();
        }
        $process = new Process(['mv', "$boxThere/output.txt", "$boxHere"]);
        $process->run();
        $output = Storage::get("$boxHereS/output.txt");
        Storage::append("$dir/output.txt", "Execute:\n$error\nOutput:\n$output");
        return $execute;
    }
}