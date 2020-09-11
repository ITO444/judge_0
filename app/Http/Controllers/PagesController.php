<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Jobs\ProcessSubmission;
use Illuminate\Support\Facades\Validator;

class PagesController extends Controller
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
        return view('pages.index');
    }

    public function user(User $user){
        return view('pages.user')->with('user', $user);
    }

    public function settings(){
        return view('pages.settings')->with('user', auth()->user());
    }

    public function saveSettings(Request $request){
        $validator = Validator::make($request->all(), [
            "display" => ['required', 'string', 'max:32'],
        ]);
        if ($validator->fails()) {
            return redirect('/settings')->withErrors($validator);
        }
        $user = auth()->user();
        $user->display = $request["display"];
        $user->save();
        return redirect('/settings')->with('success', 'Info Saved');
    }
}
