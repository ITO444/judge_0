@extends('layouts.app')

@section('content')
    <h1>Contests
    @if($level >= 6)
        <a href="/admin/contest" class="btn btn-primary float-right">New Contest</a>
    @endif
    </h1>
    <a href="/contests" class="btn btn-primary">View Ongoing / Upcoming Contests</a>
    <hr/>
    <h3>All Contests</h3>
    @if(count($contests) > 0)
        <div class="table-responsive"><table class="table table-striped table-bordered table-hover text-nowrap">
            <thead><tr>
                <th>Contest ID</th>
                <th>Name</th>
                <th class="text-center">Start</th>
                <th class="text-center">End</th>
                <th class="text-center">Duration</th>
                <th class="text-center">Contestants</th>
                <th class="text-center">Actions</th>
            </tr></thead>
            <tbody>
            @foreach($contests as $contest)
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
                        @if($contest->published && $level >= $contest->add_level)
                            <a href="/contest/{{$contest->contest_id}}/edit/contestants" class="btn btn-success btn-sm">Add Participants</a>
                        @endif
                        @if($level >= $contest->edit_level && ($level != 5 || $contest->edit_level != 4) && (!$contest->published || $level >= 6))
                            <a href="/contest/{{$contest->contest_id}}/edit" class="btn btn-primary btn-sm">Edit</a>
                        @endif
                        <a href="/submissions/contest/{{$contest->contest_id}}" class="btn btn-info btn-sm">Submissions</a>
                        <a href="/contest/{{$contest->contest_id}}/editorial" class="btn btn-secondary btn-sm">Editorial</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table></div>
    @else
        <p>No contests at all</p>
    @endif
@endsection