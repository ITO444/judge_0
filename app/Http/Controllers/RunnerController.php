<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Jobs\ProcessSubmission;
use App\Jobs\ProcessRunner;
use Illuminate\Support\Facades\Storage;
use App\Helpers\Run;

class RunnerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $directory = '/usercode/'.auth()->user()->id;
        if(Storage::missing($directory)){
            Storage::makeDirectory($directory);
            Storage::put("$directory/program.cpp", '');
            Storage::put("$directory/program.py", '');
            Storage::put("$directory/input.txt", '');
            Storage::put("$directory/output.txt", '');
        }
        $code = Storage::get("$directory/program.cpp");
        $input = Storage::get("$directory/input.txt");
        return view('pages.runner')->with('code', $code)->with('input', $input);
    }

    public function run(Request $request){
        $userId = auth()->user()->id;
        $user = User::find($userId);
        if($user->runner_status != ''){
            return;
        }
        $user->runner_status = 'On Queue';
        $user->save();
        $data = [
            'userId' => $userId,
            'code' => $request->input('code'),
            'input' => $request->input('input'),
            'language' => $request->input('language'),
        ];
        dispatch(new ProcessRunner($data));
        return;
    }

    public function save(Request $request){
        $code = $request->input('code');
        $input = $request->input('input');
        $language = $request->input('language');
        Run::saveRunner($language, $code, $input);
        return;
    }

    public function language(Request $request){
        $language = $request->input('language');
        $directory = '/usercode/'.auth()->user()->id;
        $code = Storage::get("$directory/program.$language");
        return response()->json([
            'code' => $code
        ]);
    }

    public function check(){
        $userId = auth()->user()->id;
        $user = User::find($userId);
        $status = $user->runner_status;
        $result = Storage::get("/usercode/$userId/output.txt");
        if($status == 'Compilation Error' or $status == 'Runtime Error' or $status == 'Done'){
            $user->runner_status = '';
            $user->save();
            $done = true;
        }else{
            if($status == ''){
                $done = true;
            }else{
                $done = false;
            }
        }
        return response()->json([
            'status' => $status,
            'result' => $result,
            'done' => $done
        ]);
    }
}
