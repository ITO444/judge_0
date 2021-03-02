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
        $code = Storage::get("$directory/program.cpp");
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
        $saved = $this->validateAndSave($request);
        if (!$saved) {
            return response()->json([
                'status' => "Error running"
            ]);
        }
        ProcessRunner::dispatch($userId, $request['language'])->onQueue('code');
        $user->runner_status = 'On Queue';
        $user->save();
        return response()->json([
            'status' => 0
        ]);
    }

    public function save(Request $request){
        $saved = $this->validateAndSave($request);
        if (!$saved) {
            return response()->json([
                'status' => 'Error saving'
            ]);
        }
        return response()->json([
            'status' => 'Saved'
        ]);
    }

    public function language(Request $request){
        $saved = $this->validateAndSave($request);
        if (!$saved) {
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
     * @return bool
     */
    public static function validateAndSave($request)
    {
        $validator = Validator::make($request->all(), [
            "code" => ['nullable', 'string', 'max:131072'],
            "input" => ['nullable', 'string', 'max:67108864'],
            "language" => ['required', 'string', 'in:cpp,py'],
        ]);
        if ($validator->fails()) {
            return False;
        }
        $code = $request['code'];
        $input = $request['input'];
        $language = $request['language'];
        $directory = '/usercode/'.auth()->user()->id;

        Storage::put("$directory/program.$language", $code);
        Storage::put("$directory/input.txt", $input);
        return True;
    }
}
