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
                @include('contests.contests')
            @endforeach
            </tbody>
        </table></div>
    @else
        <p>No contests at all</p>
    @endif
@endsection