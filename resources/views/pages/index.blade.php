@extends('layouts.app')

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
            <p>Hi {{auth()->user()->display}}!</p><hr/>
            We strongly advise you all to go to HKOI team trainings.<br/>
            <a href="https://hkoi.org/en/schedule-2021/">Here</a> is the training schedule.<br/>
            <a href="https://hkoi.org/en/training-team-general-information/#join">Here</a> is some general information for the training team.<br/>
            Request to join <a href="https://groups.google.com/a/online.hkoi.org/g/training2021">this Google Group</a> to participate in trainings.<br/>
            Thank you for your attention!
        @endif
    </div>
@endsection