<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Jobs\ProcessRunner;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Events\UpdateRunner;

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
        $code = Storage::get("$directory/program.py");
        $input = Storage::get("$directory/input.txt");
        return view('pages.runner')->with('code', $code)->with('input', $input)->with('output', Storage::get("$directory/output.txt"));
    }

    public function run(Request $request){
        $user = auth()->user();
        $userId = $user->id;
        if($user->runner_status != ''){
            return response()->json([
                'status' => "Please be patient"
            ]);
        }
        if (!$this->validateData($request)) {
            return response()->json([
                'status' => "Error running"
            ]);
        }
        $this->saveData($request);
        ProcessRunner::dispatch($userId, $request['language'])->onQueue('code');
        $user->runner_status = 'On Queue';
        $user->save();
        return response()->json([
            'status' => 0
        ]);
    }

    public function save(Request $request){
        if (!$this->validateData($request)) {
            return response()->json([
                'status' => 'Error saving'
            ]);
        }
        $this->saveData($request);
        return response()->json([
            'status' => 'Saved'
        ]);
    }

    public function language(Request $request){
        if (!$this->validateData($request)) {
            return response()->json([
                'status' => 'Error switching'
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

    /**
     * Saves the user's code for runner
     *
     * @param Request $request
     * @return
     */
    public static function saveData($request)
    {
        $code = $request['code'];
        $input = $request['input'];
        $language = $request['language'];
        $directory = '/usercode/'.auth()->user()->id;

        Storage::put("$directory/program.$language", $code);
        Storage::put("$directory/input.txt", $input);
    }

    /**
     * Saves the user's code for runner
     *
     * @param Request $request
     * @return bool
     */
    public static function validateData($request)
    {
        $validator = Validator::make($request->all(), [
            "code" => ['nullable', 'string', 'max:131072'],
            "input" => ['nullable', 'string', 'max:67108864'],
            "language" => ['required', 'string', 'in:cpp,py'],
        ]);
        if ($validator->fails()) {
            return False;
        }
        return True;
    }
}
