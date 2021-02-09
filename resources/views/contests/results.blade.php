@php
use Carbon\Carbon;
@endphp
@extends('layouts.app')

@section('pageTitle', "$contest->contest_id - $contest->name")

@section('content')
    @include("contests.top")
    <h1>Results</h1>
    <hr/>
    @if(count($contest->participations) > 0)
        <div class="table-responsive"><table class="table table-striped table-bordered table-hover text-nowrap">
            <thead><tr>
                <th class="text-center">Rank</th>
                <th>Contestant</th>
                <th class="text-center">Type</th>
                <th class="text-center">Status</th>
                @foreach($contest->tasksConfig() as $task => $config)
                    <th class="text-center">
                        {{App\Task::find($task)->task_id}}
                    </th>
                @endforeach
                <th class="text-center">Extra</th>
                <th class="text-center">Score</th>
            </tr></thead>
            <tbody>
            @foreach($contest->participations as $key => $participation)
                <tr class="{{$participation->user->id == auth()->user()->id ? 'table-primary' : ''}}">
                    <td class="text-center">
                        @if($participation->isUpcoming() || !$participation->type)
                        <span class="text-muted">-</span>
                        @elseif(!$loop->first && $participation->score == $participations[$key - 1]->score)
                        {{$rank}}
                        @else
                        {{$rank = $loop->iteration}}
                        @endif
                    </td>
                    <td>
                        {{$participation->user->name}} - {{$participation->user->display}}
                    </td>
                    <td class="text-center">
                        {{$participation->type ? "Official" : "Unofficial"}}
                    </td>
                    <td class="text-center">
                        {{$participation->isUpcoming() ? 'Upcoming' : ($participation->isOngoing() ? 'In progress' : 'Ended')}}
                    </td>
                    @if($participation->isUpcoming())
                        @foreach($contest->tasksConfig() as $task => $config)
                            <td class="text-center"></td>
                        @endforeach
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                    @else
                        @foreach($contest->tasksConfig() as $task => $config)
                            <td class="text-center">
                                @php
                                    $time = $participation->information['tasks'][$task]['solve_time'];
                                    if($time !== null){
                                        $time = Carbon::parse($participation->start)->diffInSeconds($time);
                                    }
                                @endphp
                                @if($time !== null)
                                <span class="{{$participation->submissions->where('result', 'Accepted')->where('task_id', $task)->isNotEmpty() ? ' text-success font-weight-bold' : ''}}">{{$participation->information['tasks'][$task]['score'] / 1000}}</span>
                                <br/>
                                <small class="text-muted">{{gmdate('H:i:s', $time)}}</small>
                                @endif
                            </td>
                        @endforeach
                        <td class="text-center">
                            {{$participation->information['extra']}}
                        </td>
                        <td class="text-center font-weight-bold">
                            {{$participation->isUpcoming() ? '' : $participation->score / 1000}}
                        </td>
                    @endif
                </tr>
            @endforeach
            </tbody>
        </table></div>
    @else
        <p>No contestants at all</p>
    @endif
@endsection