<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Task;
use App\Test;
use App\Contest;
use App\Participation;
use App\Submission;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use App\Jobs\ProcessSubmission;
use App\Jobs\PublishGrader;
use App\Helpers\BB;
use Carbon\Carbon;

class ContestsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($page = NULL)
    {
        $level = auth()->user()->level;
        if($page == "all"){
            $contests = Contest::where('view_level', '<=', $level)->where(function ($query) use ($level) {
                $query->where('published', '=', 1)
                    ->orWhere(function ($query) use ($level) {
                            $query->where('edit_level', '<=', $level);
                            if($level == 5){
                                $query->where('edit_level', '<>', 4);
                            }
                        });
            });
            return view('contests.all')->with('contests', $contests->paginate(50))->with('level', $level);
        }
        if($page == null){
            $now = Carbon::now()->timestamp;
            $contests = Contest::where('view_level', '<=', $level)->where('published', '=', 1);
            $ongoing = $contests->where('start', '<=', $now)->where('end', '>', $now)->get();
            $upcoming = $contests->where('start', '>', $now)->get();
            return view('contests.index')->with('ongoing', $ongoing)->with('upcoming', $upcoming)->with('level', $level);
        }
        abort(404);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('contests.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "contest_id" => ['required', 'string', "unique:contests,contest_id", "max:10"],
            "name" => ['required', 'string', "max:255"],
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator);
        }
        $level = auth()->user()->level;
        $contest = new Contest;
        $contest->contest_id = $request['contest_id'];
        $contest->name = $request['name'];
        $contest->start = $contest->freshTimestamp();
        $contest->end = $contest->freshTimestamp();
        $contest->results = $contest->freshTimestamp();
        $contest->duration = 0;
        $contest->view_level = $level;
        $contest->reg_level = $level;
        $contest->add_level = $level;
        $contest->edit_level = $level;
        $contest->description = '';
        $contest->editorial = '';
        $contest->configuration = ["tasks" => [], "feedback" => True, "cumulative" => True];
        $contest->save();
        return redirect("/contest/$contest->contest_id/edit")->with('success', 'Contest Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Contest $contest)
    {
        $level = auth()->user()->level;
        if(!($level >= $contest->view_level && ($contest->published || ($level >= $contest->edit_level && ($level != 5 || $contest->edit_level != 4))))){
            return abort(404);
        }
        $contest->description = BB::convertToHtml($contest->description);
        return view('contests.show')->with('contest', $contest)->with('level', $level);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Contest $contest)
    {
        $level = auth()->user()->level;
        if(!($level >= $contest->edit_level && ($level != 5 || $contest->edit_level != 4) && (!$contest->published || $level >= 6))){
            return abort(404);
        }
        return view('contests.edit')->with('contest', $contest)->with('level', $level);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contest $contest)
    {
        $level = auth()->user()->level;
        if(!($level >= $contest->edit_level && ($level != 5 || $contest->edit_level != 4)) || $contest->published){
            return abort(404);
        }
        $editLevelMin = $level == 5 ? 5 : 4;
        $validator = Validator::make($request->all(), [
            "contest_id" => ['required', 'string', "unique:contests,contest_id,$contest->id", 'max:10'],
            "name" => ['required', 'string', 'max:255'],
            "view_level" => ['nullable', 'integer', "between:1, $level"],
            "reg_level" => ['nullable', 'integer', "between:1, $level"],
            "add_level" => ['nullable', 'integer', "between:4, $level"],
            "edit_level" => ['nullable', 'integer', "between:$editLevelMin, $level"],
            "start" => ['required', 'date'],
            "end" => ['required', 'date'],
            "duration" => ['required', 'date_format:H:i', 'after_or_equal:00:00', 'before_or_equal:23:59'],
            "results" => ['required', 'date'],
            "feedback" => ['boolean'],
            "cumulative" => ['boolean'],
            "description" => ['nullable', 'string', 'max:65535'],
            "editorial" => ['nullable', 'string', 'max: 65535'],
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator);
        }
        $infos = [];
        $contest->contest_id = $request["contest_id"];
        $contest->name = $request["name"];
        
        $contest->start = $request["start"];
        $contest->end = $request["end"];
        $contest->duration = Carbon::parse($request["duration"])->secondsSinceMidnight();
        $diffInSeconds = Carbon::parse($contest->end)->diffInSeconds(Carbon::parse($contest->start));
        if($contest->duration != $diffInSeconds){
            $infos[] = "Note that the contest duration is not the same as the time between the start and end of the contest (You may ignore this if it is intended)";
        }

        $contest->view_level = $request["view_level"] ?: $level;
        $contest->reg_level = $request["reg_level"] ?: $level;
        if($contest->reg_level < $contest->view_level){
            $infos[] = "We automatically adjusted the register level from $contest->reg_level to $contest->view_level so that it is not lower than the view level";
            $contest->reg_level = $contest->view_level;
        }
        $contest->add_level = $request["add_level"] ?: $level;
        if($contest->add_level < $contest->reg_level){
            $infos[] = "We automatically adjusted the add user level from $contest->add_level to $contest->reg_level so that it is not lower than the register level";
            $contest->add_level = $contest->reg_level;
        }
        $contest->edit_level = $request["edit_level"] ?: $level;
        if($contest->edit_level < $contest->add_level){
            $infos[] = "We automatically adjusted the edit level from $contest->edit_level to $contest->add_level so that it is not lower than the add user level";
            $contest->edit_level = $contest->add_level;
        }

        $configuration = $contest->configuration;
        $configuration["feedback"] = $request["feedback"] ? true : false;
        $configuration["cumulative"] = $request["cumulative"] ? true : false;
        $contest->configuration = $configuration;

        $contest->results = $request["results"];
        $contest->description = $request["description"] ?: '';
        $contest->editorial = $request["editorial"] ?: '';
        $contest->save();
        return redirect("/contest/$contest->contest_id/edit")->with('success', "Saved")->with('infos', $infos);
    }

    public function editorial(Contest $contest)
    {
        $user = auth()->user();
        $level = $user->level;
        if(!($level >= $contest->edit_level && ($level != 5 || $contest->edit_level != 4) && (!$contest->published || $level >= 6))){
            return abort(404);
        }
        $contest->editorial = BB::convertToHtml($contest->editorial);
        return view('contests.editorial')->with('contest', $contest)->with('level', $level);
    }
    
    public function editTasks(Contest $contest){
        return view('contests.tasks');
    }
    
    public function updateTasks(Request $request, Contest $contest){
        return view('contests.tasks');
    }

    public function editTask(Contest $contest){
        return view('contests.tasks');
    }
    
    public function updateTask(Request $request, Contest $contest){
        return view('contests.tasks');
    }

    public function deleteTask(Request $request, Contest $contest){
        return view('contests.tasks');
    }
    
    public function editContestants(Contest $contest){
        return view('contests.tasks');
    }
    
    public function addContestant(Request $request, Contest $contest){
        return view('contests.tasks');
    }
    
    public function deleteContestant(Request $request, Contest $contest){
        return view('contests.tasks');
    }

    public function register(Request $request, Contest $contest){
        $user = auth()->user();
        if($user->level < $contest->reg_level || !$contest->published){
            return abort(404);
        }
        $validator = Validator::make($request->all(), [
            "start" => ['required', 'date'],
            "type" => ['boolean'],
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $time = Carbon::parse($request["start"]);
        $start = $time;
        $end = $time->addSeconds($contest->duration);
        $type = $request['type'] == true ? 0 : 1;

        if($start < $contest->start){
            return back()->with("error", "You may not start the contest before the official start of the contest");
        }
        if($contest->doneBy($user)){
            return back()->with("error", "You are already in this contest");
        }
        if($contest->hasOverlap($user, $start)){
            return back()->with("error", "You are in another contest in the chosen time period");
        }
        if($type && $end > $contest->end){
            return back()->with("error", "You may not participate officially if you end after the official end of the contest");
        }

        $participation = new Participation;
        $participation->user_id = $user->id;
        $participation->contest_id = $contest->id;
        $participation->type = $type;
        $participation->start = $start;
        $participation->end = $end;
        $participation->score = 0;

        $information = ['tasks' => [], 'extra' => 0];
        $configuration = $contest->configuration;
        foreach($configuration['tasks'] as $taskId => $taskConfig){
            $information['tasks'][$taskId]['solve_time'] = null;
            $information['tasks'][$taskId]['subtasks'] = [];
            foreach($taskConfig['subtasks'] as $subtask => $score){
                $information['tasks'][$taskId]['subtasks'][$subtask] = 0;
            }
        }
        $participation->information = $information;
        $participation->save();
        return back()->with("success", "Registered");
    }
    
    public function unregister(Request $request, Contest $contest){
        $user = auth()->user();
        if(!$contest->canUnreg($user)){
            abort(404);
        }
        $participation = $contest->participationOf($user);
        $participation->delete();
        return back()->with('success', 'Should I use green for a successful unregistration?')->with('error', 'Or should I use red since it\'s sad you unregistered?');
    }
    
    public function publish(Contest $contest)
    {
        $level = auth()->user()->level;
        if($level < $contest->edit_level || $contest->published){
            return abort(404);
        }
        /*$unpublished = $contest->tasks()->where('published', 0);
        if(count($unpublished)){
            $infos = [];
            foreach($unpublished as $task){
                $infos[] = "$task->task_id - $task->title";
            }
            return back()->with('infos', $infos)->with('error', 'Please publish the following tasks before publishing the contest');
        }*/
        $contest->published = 1;
        $contest->save();
        return back()->with('success', 'Published');
    }

    public function unpublish(Contest $contest)
    {
        $level = auth()->user()->level;
        if($level < $contest->edit_level || !$contest->published){
            return abort(404);
        }
        if($contest->participations->isNotEmpty()){
            return back()->with('error', 'Please unregister all contestants before unpublishing this contest');
        }
        $contest->published = 0;
        $contest->save();
        return back()->with('success', 'Unpublished');
    }
}
