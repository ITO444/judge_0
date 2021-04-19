<?php

namespace App\Helpers;

use App\User;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class Run
{
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
        if(strlen($error) > 5000){
            $error = substr($error, 0, 5000).'...';
        }
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
        $wallTime = $compileTime * 10;
        if($language == "cpp"){
            $runCommand = new Process([
                'isolate', '--cg', "--box-id=$boxId", "--time=$compileTime", "--wall-time=$wallTime",
                "--cg-mem=$compileMemory", '--processes',
                "--env=PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin",
                "--meta=$boxHere/meta.txt", '--run', '--',
                '/usr/bin/g++', '-static', '-Wno-unused-result',
                '-DONLINE_JUDGE', '-s', '-O2', '-o', 'program.exe', 'program.cpp'
            ]);
        }else if($language == "py"){
            $runCommand = new Process([
                'isolate', '--cg', "--box-id=$boxId", "--time=$compileTime", "--wall-time=$wallTime",
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
        $wallTime = $runtimeLimit * 10;
        if($language == "cpp"){
            chmod("$boxHere/program.exe", 0764);
            $runCommand = new Process([
                'isolate', '--cg', "--box-id=$boxId", "--time=$runtimeLimit", "--wall-time=$wallTime",
                "--cg-mem=$memoryLimit", "--fsize=$outputLimit", '--processes',
                "--stdin=input.txt", "--stdout=output.txt",
                "--meta=$boxHere/meta.txt", '--run', '--',
                'program.exe'
            ]);
        }else if($language == "py"){
            $runCommand = new Process([
                'isolate', '--cg', "--box-id=$boxId", "--time=$runtimeLimit", "--wall-time=$wallTime",
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

    /**
     * Grades code
     *
     * @param int $boxId
     * @return array
     */
    public static function grade(int $boxId)
    {
        $boxHere = base_path()."/storage/app/run/$boxId";
        $boxThere = "/var/local/lib/isolate/$boxId/box";
        chmod("$boxHere/grader.exe", 0764);
        $runCommand = new Process([
            'isolate', '--cg', "--box-id=$boxId", "--time=10", "--wall-time=20",
            "--cg-mem=1048576", "--fsize=65536", '--processes',
            "--meta=$boxHere/meta.txt", '--run', '--',
            'grader.exe', 'input.txt', 'output.txt', 'answer.txt', 'result.txt'
        ]);
        $data = Run::isolate($boxId, $runCommand);
        $process = new Process(['mv', "$boxThere/result.txt", "$boxHere/result.txt"]);
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