<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Task;
use App\Test;
use App\Submission;

class SubmissionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $myLevel = auth()->user()->level;
        if($myLevel == 4){
            $myLevel = 7;
        }
        $submissions = Submission::orderBy('id', 'desc')->paginate(50);
        return view('submissions.index')->with('submissions', $submissions)->with('myLevel', $myLevel);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Submission $submission)
    {
        $myLevel = auth()->user()->level;
        if($myLevel == 4){
            $myLevel = 7;
        }
        if($myLevel < $submission->task->view_level){
            return abort(404);
        }
        return view('submissions.show')->with('submission', $submission)->with('myLevel', $myLevel);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
