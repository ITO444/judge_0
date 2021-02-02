<tr class="{{$contest->doneBy(auth()->user()) ? ($loop->iteration % 2 ? 'table-primary' : 'table-info') : ''}}">
    <td>{{$contest->contest_id}}</td>
    <td>
        @if(!$contest->published)
        <span class="badge badge-danger">WIP</span>
        @endif
        <a href="/contest/{{$contest->contest_id}}">{{$contest->name}}</a>
    </td>
    <td class="text-center">
        {{$contest->start}}
    </td>
    <td class="text-center">
        {{$contest->end}}
    </td>
    <td class="text-center">
        {{gmdate("G \h i \m", $contest->duration)}}
    </td>
    <td class="text-center">
        {{$contest->participations->count()}}
    </td>
    <td class="text-center">
        @if(!($level < $contest->add_level || ($level == 5 && $contest->add_level == 4) || !$contest->published))
            <a href="/contest/{{$contest->contest_id}}/edit/contestants" class="btn btn-success btn-sm">Manage Contestants</a>
        @endif
        @if($level >= $contest->edit_level && ($level != 5 || $contest->edit_level != 4) && (!$contest->published || $level >= 6))
            <a href="/contest/{{$contest->contest_id}}/edit" class="btn btn-primary btn-sm">Edit</a>
        @endif
        @if($contest->canSeeResults(auth()->user()))
            <a href="/contest/{{$contest->contest_id}}/results" class="btn btn-dark btn-sm">Results</a>
        @endif
        @if($contest->canSeeSubmissions(auth()->user()))
        <a href="/submissions/contest/{{$contest->contest_id}}" class="btn btn-info btn-sm">Submissions</a>
        @endif
        @if(!($level < $contest->edit_level || ($level == 5 && $contest->edit_level == 4)) || !(!$contest->hasEnded() || $level < $contest->view_level))
            <a href="/contest/{{$contest->contest_id}}/editorial" class="btn btn-secondary btn-sm">Editorial</a>
        @endif
    </td>
</tr>