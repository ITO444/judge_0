<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Jobs\ProcessSubmission;

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

    public function user($id){
        $user = User::find($id);
        if(!$user){
            return redirect('/'.auth()->user()->id);
        }
        return view('pages.user')->with('user', $user);
    }

    public function settings(){
        return view('pages.index');
    }

    public function queue(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $input = $_POST['input'];
            $data = [
                'in' => $_POST['input'],
                'sleep' => $_POST['sleep'],
            ];
            dispatch(new ProcessSubmission($data));
        }
        $output = file_get_contents(env('APP_PATH')."resources/testing/queue.txt");
        return view('pages.queue')->with('output', $output);
    }
}
