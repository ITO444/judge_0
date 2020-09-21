<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
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
        return view('admin.index')->with('level', auth()->user()->level);
    }

    public function viewUsers(){
        $users = User::paginate(20);
        return view('admin.users')->with('users', $users);
    }

    public function saveUser(Request $request, User $user){
        $myLevel = auth()->user()->level;
        $validator = Validator::make($request->all(), [
            "name" => ['required', 'string', 'max:32', "unique:users,name,$user->id"],
            "real_name" => ['required', 'string', 'max:255'],
            "display" => ['required', 'string', 'max:32'],
            "email" => ['required', 'string', 'email', 'max:255', "unique:users,email,$user->id"],
            "level" => ['required', 'integer', "between:0, $myLevel"],
        ]);
        if ($validator->fails()) {
            return redirect('/admin/users')->withErrors($validator);
        }
        if($myLevel <= 5 && !$user->google_id){
            return abort(404);
        }
        if($myLevel <= $user->level){
            return redirect('/admin/users')->with('error', 'The level you set cannot be higher than your level');
        }
        $user->name = $request["name"];
        $user->real_name = $request["real_name"];
        $user->display = $request["display"];
        $user->email = $request["email"];
        $user->level = $request["level"];
        $user->save();
        return redirect('/admin/users')->with('success', 'Info Saved');
    }

    public function lesson(){
        return view('lesson.index');
    }

    public function attend(){
        return view('lesson.attend')->with("attend", auth()->user()->attendance ? 'Choose "Leave training" if training is over' : 'Choose "Attend training" if it is now training');
    }

    public function saveAttend(Request $request){
        $validator = Validator::make($request->all(), [
            "attendance" => ['required', 'integer', 'between:0, 1'],
        ]);
        if ($validator->fails()) {
            return redirect('/lesson/attend')->withErrors($validator);
        }
        $user = auth()->user();
        $user->attendance = $request["attendance"];
        $user->save();
        $message = $user->attendance ? "Welcome to programming training!" : "Bye, have a nice day!";
        return redirect('/lesson/attend')->with('success', $message);
    }

    public function answer(){
        if(!auth()->user()->attendance){
            return abort(404);
        }
        return view('lesson.answer');
    }

    public function saveAnswer(Request $request){
        if(!auth()->user()->attendance){
            return abort(404);
        }
        $validator = Validator::make($request->all(), [
            "answer" => ['nullable', 'string', 'max:65535'],
        ]);
        if ($validator->fails()) {
            return redirect('/lesson/answer')->withErrors($validator);
        }
        $user = auth()->user();
        $user->answer = $request["answer"];
        $user->save();
        return redirect('/lesson/answer')->with('success', "Thank you for your answer");
    }

    public function adminLesson($language){
        if($language !== "cpp" && $language !== "py"){
            return abort(404);
        }
        $users = User::where("attendance", 1)->paginate(20);
        return view('admin.lesson')->with("users", $users)->with("language", $language);
    }
}
