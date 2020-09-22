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
     * Runs isolate
     *
     * @param int $boxId
     * @param Process $runCommand
     * @return array
     */
    public static function isolate(int $boxId, Process $runCommand)
    {
        $boxHere = base_path()."/storage/app/run/$boxId";
        $boxThere = "/var/local/lib/isolate/$boxId/box";
        
        $process = new Process(['isolate', '--cg', '-b', $boxId, '--cleanup']);
        $process->run();
        $process = new Process(['isolate', '--cg', '-b', $boxId, '--init']);
        $process->run();

        $process = Process::fromShellCommandline("mv $boxHere/* $boxThere");
        $process->run();

        $exitCode = $runCommand->run();
        $error = $runCommand->getErrorOutput();
        return ['error' => $error];
    }

    /**
     * Compiles code
     *
     * @param int $boxId
     * @param int $compileTime
     * @param int $compileMemory
     * @param string $language
     * @return array
     */
    public static function compile(int $boxId, int $compileTime, int $compileMemory, string $language)
    {
        $boxHere = base_path()."/storage/app/run/$boxId";
        $boxThere = "/var/local/lib/isolate/$boxId/box";
        if($language == "cpp"){
            $runCommand = new Process([
                'isolate', '--cg', "--box-id=$boxId", "--time=$compileTime",
                "--cg-mem=$compileMemory", '--processes',
                "--env=PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin",
                "--meta=$boxHere/meta.txt", '--run', '--',
                '/usr/bin/g++', '-static', '-Wno-unused-result',
                '-DONLINE_JUDGE', '-s', '-O2', '-o', 'program.exe', 'program.cpp'
            ]);
        }else if($language == "py"){
            $runCommand = new Process([
                'isolate', '--cg', "--box-id=$boxId", "--time=$compileTime",
                "--cg-mem=$compileMemory", '--processes',
                "--meta=$boxHere/meta.txt", '--run', '--',
                '/usr/bin/python3', '-S', '-m', 'py_compile', 'program.py'
            ]);
        }
        $data = Run::isolate($boxId, $runCommand);
        if($language == "cpp"){
            $process = new Process(['mv', "$boxThere/program.exe", "$boxHere/program.exe"]);
            $process->run();
        }
        $lines = explode("\n", Storage::get("/run/$boxId/meta.txt"));
        foreach ($lines as $line) {
            if($line){
                list($key, $value) = explode(":", $line);
                $data[$key] = $value;
            }
        }
        return $data;
    }

    /**
     * Executes code
     *
     * @param int $boxId
     * @param int $runtimeLimit
     * @param int $memoryLimit
     * @param int $outputLimit
     * @param string $language
     * @return array
     */
    public static function execute(int $boxId, int $runtimeLimit, int $memoryLimit, int $outputLimit, string $language)
    {
        $boxHere = base_path()."/storage/app/run/$boxId";
        $boxThere = "/var/local/lib/isolate/$boxId/box";
        if($language == "cpp"){
            $process = new Process(["chmod", "764", "$boxHere/program.exe"]);
            $process->run();
            $runCommand = new Process([
                'isolate', '--cg', "--box-id=$boxId", "--time=$runtimeLimit",
                "--cg-mem=$memoryLimit", "--fsize=$outputLimit", '--processes',
                "--stdin=input.txt", "--stdout=output.txt",
                "--meta=$boxHere/meta.txt", '--run', '--',
                'program.exe'
            ]);
        }else if($language == "py"){
            $runCommand = new Process([
                'isolate', '--cg', "--box-id=$boxId", "--time=$runtimeLimit",
                "--cg-mem=$memoryLimit", "--fsize=$outputLimit", '--processes',
                "--stdin=input.txt", "--stdout=output.txt",
                "--meta=$boxHere/meta.txt", '--run', '--',
                '/usr/bin/python3', '-O', '-S', 'program.py'
            ]);
        }
        $data = Run::isolate($boxId, $runCommand);
        $process = new Process(['mv', "$boxThere/output.txt", "$boxHere/output.txt"]);
        $process->run();
        $lines = explode("\n", Storage::get("/run/$boxId/meta.txt"));
        foreach ($lines as $line) {
            if($line){
                list($key, $value) = explode(":", $line);
                $data[$key] = $value;
            }
        }
        return $data;
    }
}