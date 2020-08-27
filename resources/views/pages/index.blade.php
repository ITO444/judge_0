@extends('layouts.app')

@section('content')
    <div class="jumbotron text-center">
        <h1>Judge</h1>
        @if(Auth::guest())
            <p>[insert some text here]</p>
            <a href="{{ url('redirect') }}">
                Login as DGS Student/Staff
            </a>
            <a href="{{ url('login') }}">
                Login as Others
            </a>
        @else
            <p>Welcome {{Auth::user()->name}}, what would you like to do today?</p>
            <a href="/test" class="btn btn-primary">Test</a>
            <a href="/queue" class="btn btn-primary">Queue</a>
            <a href="/users/{{Auth::user()->id}}" class="btn btn-primary">User</a>
            <a href="/home" class="btn btn-primary">Home</a>
        @endif
    </div>
@endsection