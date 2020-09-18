<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Jobs\ProcessSubmission;
use App\Jobs\ProcessRunner;
use Illuminate\Support\Facades\Storage;
use App\Helpers\Run;
use Illuminate\Support\Facades\Validator;

class RunnerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
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
        $validator = Validator::make($request->all(), [
            "code" => ['nullable', 'string', 'max:131072'],
            "input" => ['nullable', 'string', 'max:67108864'],
            "language" => ['required', 'string', 'in:cpp,py'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => "Cannot run"
            ]);
        }
        $user->runner_status = 'On Queue';
        $user->save();
        $data = [
            'userId' => $userId,
            'code' => $request['code'],
            'input' => $request['input'],
            'language' => $request['language'],
        ];
        ProcessRunner::dispatch($data)->onQueue('code');
        return response()->json([
            'status' => 0
        ]);
    }

    public function save(Request $request){
        $validator = Validator::make($request->all(), [
            "code" => ['nullable', 'string', 'max:131072'],
            "input" => ['nullable', 'string', 'max:67108864'],
            "language" => ['required', 'string', 'in:cpp,py'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'Cannot save'
            ]);
        }
        $code = $request['code'];
        $input = $request['input'];
        $language = $request['language'];
        Run::saveRunner($language, $code, $input);
        return response()->json([
            'status' => 'Saved'
        ]);
    }

    public function language(Request $request){
        $validator = Validator::make($request->all(), [
            "language" => ['required', 'string', 'in:cpp,py'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'Cannot switch'
            ]);
        }
        $language = $request['language'];
        $directory = '/usercode/'.auth()->user()->id;
        $code = Storage::get("$directory/program.$language");
        return response()->json([
            'code' => $code,
            'status' => 'Switched'
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
            'result' => htmlspecialchars($result),
            'done' => $done
        ]);
    }
}
