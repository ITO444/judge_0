@extends('layouts.app')

@section('content')
    <h1>Leaderboard
    </h1>
    <br/>
    @if(count($users) > 0)
    {{$users->links()}}
        <div class="table-responsive"><table class="table table-striped table-bordered table-hover text-nowrap">
            <thead><tr>
                <th class="text-center">Rank</th>
                <th>User</th>
                <th class="text-center">Solved</th>
            </tr></thead>
            <tbody>
            @foreach($users as $user)
                <tr class="{{$user->id == auth()->user()->id ? 'table-primary' : ''}}">
                    <td class="text-center">{{($users->currentPage() - 1) * 100 + $loop->iteration}}</td>
                    <td><a href="/user/{{$user->name}}">{{$user->name}} - {{$user->display}}</a></td>
                    <td class="text-center">{{$user->solved}}</td>
                </tr>
            @endforeach
            </tbody>
        </table></div>
        {{$users->links()}}
    @else
        <p>No tasks found</p>
    @endif
@endsection