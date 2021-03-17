@extends('layouts.app')

@section('pageTitle', "Home")

@section('content')
    <div class="jumbotron text-center">
        <h1>{{config('app.name')}}</h1>
        @if(Auth::guest())
            <a href="{{ url('redirect') }}" class="btn btn-primary">
                Login as DGS Student/Staff
            </a>
            <a href="{{ url('login') }}" class="btn btn-primary">
                Login as Others
            </a>
        @else
            <p>Hi {{auth()->user()->display}}!</p>
            <hr/>
            Welcome to the DGS Programming Team Online Judge.<br/>
            <br/>
            Feel free to click around and try doing various tasks!<br/>
            <br/>
            We recommend you to try doing <a href="task/P000">P000 - Output</a> to learn basic output in Python.<br/>
            <br/>
            <strong>You may fill in <a href="https://forms.gle/buigXNjhAEqBS2397" target="_blank">this Google form</a> after completing P000 to earn points for your house in the 2021 Inter-House Programming Contest.</strong><br/>
            {{-- We strongly advise you all to go to HKOI team trainings.<br/>
            <a href="https://hkoi.org/en/schedule-2021/">Here</a> is the training schedule.<br/>
            <a href="https://hkoi.org/en/training-team-general-information/#join">Here</a> is some general information for the training team.<br/>
            Request to join <a href="https://groups.google.com/a/online.hkoi.org/g/training2021">this Google Group</a> to participate in trainings.<br/>
            Thank you for your attention!--}}
        @endif
    </div>
@endsection