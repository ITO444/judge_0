@extends('layouts.app')

@section('content')
    <div class="jumbotron text-center">
        <h1>{{config('app.name')}}</h1>
        Testing LaTeX
        <p>
            When \(a \ne 0\), there are two solutions to \(ax^2 + bx + c = 0\) and they are
            \[x = {-b \pm \sqrt{b^2-4ac} \over 2a}.\]
        </p>
        @if(Auth::guest())
            <a href="{{ url('redirect') }}" class="btn btn-primary">
                Login as DGS Student/Staff
            </a>
            <a href="{{ url('login') }}" class="btn btn-primary">
                Login as Others
            </a>
        @else
            <p>Welcome {{auth()->user()->display}}, what would you like to do today?</p>
            <a href="/runner" class="btn btn-primary">Runner</a>
            <a href="/user/{{auth()->user()->name}}" class="btn btn-primary">User</a>
            <a href="/home" class="btn btn-primary">Home</a>
        @endif
    </div>
@endsection