@extends('layouts.app')

@section('content')
    @include("contests.top")
    <h1>Results</h1>
    <hr/>
    @if(count($contest->participations) > 0)
        <div class="table-responsive"><table class="table table-striped table-bordered table-hover text-nowrap">
            <thead><tr>
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
            @foreach($contest->participations as $participation)
                <tr class="{{$participation->user->id == auth()->user()->id ? 'table-primary' : ''}}">
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
                                {{$participation->information['tasks'][$task]['score'] / 1000}}
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