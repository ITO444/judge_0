@extends('layouts.app')

@section('pageTitle', "Contests")

@section('content')
    <h1>Contests
    @if($level >= 6)
        <a href="/admin/contest" class="btn btn-primary float-right">New Contest</a>
    @endif
    </h1>
    <a href="/contests/all" class="btn btn-primary">View All Contests ›</a>
    <hr/>
    <h3>Ongoing Contests</h3>
    @if(count($ongoing) > 0)
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
            @foreach($ongoing as $contest)
                @include('contests.contests')
            @endforeach
            </tbody>
        </table></div>
    @else
        <p>No ongoing contests</p>
    @endif
    <hr/>
    <h3>Upcoming Contests</h3>
    @if(count($upcoming) > 0)
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
            @foreach($upcoming as $contest)
                @include('contests.contests')
            @endforeach
            </tbody>
        </table></div>
    @else
        <p>No upcoming contests</p>
    @endif
    <hr/>
    <h3>Recent Contests</h3>
    @if(count($recent) > 0)
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
            @foreach($recent as $contest)
                @include('contests.contests')
            @endforeach
            </tbody>
        </table></div>
    @else
        <p>No recent contests</p>
    @endif
    <br/>
    <a href="/contests/all" class="btn btn-primary">View All Contests ›</a>
@endsection