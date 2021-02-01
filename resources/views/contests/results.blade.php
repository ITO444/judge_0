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
                    <td class="text-center">
                        {{$participation->isUpcoming() ? '' : $participation->score}}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table></div>
    @else
        <p>No contestants at all</p>
    @endif
@endsection