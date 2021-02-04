<h1>
    <a href="/contest/{{$contest->contest_id}}" class="text-body">{{$contest->name}}</a>
    @if(!$contest->published)
    <span class="badge badge-danger">WIP</span>
    @endif
    @if($contest->doneBy(auth()->user()))
    <span class="badge badge-success">Registered</span>
    @endif
</h1>
<div class="btn-group p-1">
    <div class="btn btn-outline-secondary disabled">
        {{$contest->contest_id}}
    </div>
    <div class="btn btn-outline-secondary disabled">
        Start Time: {{$contest->start}}
    </div>
    <div class="btn btn-outline-secondary disabled">
        End Time: {{$contest->end}}
    </div>
    <div class="btn btn-outline-secondary disabled">
        Duration: {{gmdate("G \h i \m", $contest->duration)}}
    </div>
    <div class="btn btn-outline-secondary disabled">
        Contestant Count: {{$contest->participations->count()}}
    </div>
</div>
<br/>
<div class="btn-group p-1">
    @if(!($level < $contest->add_level || ($level == 5 && $contest->add_level == 4) || !$contest->published))
        <a href="/contest/{{$contest->contest_id}}/edit/contestants" class="btn btn-success">Manage Contestants</a>
    @endif
    @if($level >= $contest->edit_level && ($level != 5 || $contest->edit_level != 4) && (!$contest->published || $level >= 6))
        <a href="/contest/{{$contest->contest_id}}/edit" class="btn btn-primary">Edit</a>
    @endif
    @if($contest->canSeeResults(auth()->user()))
        <a href="/contest/{{$contest->contest_id}}/results" class="btn btn-dark">Results</a>
    @endif
    @if($contest->canSeeSubmissions(auth()->user()))
    <a href="/submissions/contest/{{$contest->contest_id}}" class="btn btn-info">Submissions</a>
    @endif
    @if(!($level < $contest->edit_level || ($level == 5 && $contest->edit_level == 4)) || !(!$contest->hasEnded() || $level < $contest->view_level || auth()->user()->contestNow() != null))
        <a href="/contest/{{$contest->contest_id}}/editorial" class="btn btn-secondary">Editorial</a>
    @endif
</div>
<hr/>