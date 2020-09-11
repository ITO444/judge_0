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
        $users = User::paginate(50);
        return view('admin.users')->with('users', $users);
    }

    public function saveUser(Request $request, $id){
        $myLevel = auth()->user()->level;
        $validator = Validator::make($request->all(), [
            "name" => ['required', 'string', 'max:32', "unique:users,name,$id"],
            "display" => ['required', 'string', 'max:32'],
            "level" => ['required', 'integer', "between:0, $myLevel"],
        ]);
        if ($validator->fails()) {
            return redirect('/admin/users')->withErrors($validator);
        }
        $user = User::find($id);
        if($myLevel <= $user->level){
            return redirect('/admin/users')->with('error', 'Your level is too low');
        }
        $user->name = $request['name'];
        $user->display = $request["display"];
        $user->level = $request["level"];
        $user->save();
        return redirect('/admin/users')->with('success', 'Info Saved');
    }
}
