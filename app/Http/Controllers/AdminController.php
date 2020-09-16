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
        $this->middleware(['auth', 'admin:4']);
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
            "display" => ['required', 'string', 'max:32'],
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
        $user->display = $request["display"];
        $user->level = $request["level"];
        $user->save();
        return redirect('/admin/users')->with('success', 'Info Saved');
    }
}
