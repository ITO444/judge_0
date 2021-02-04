@extends('layouts.app')

@section('pageTitle', "Lesson")

@section('content')
    <a href="/" class="btn btn-secondary">Back</a><br/><br/>
    <h3>Lesson</h3>
    <div class="list-group">
        <a href="/lesson/attend" class="list-group-item list-group-item-action">
            Attend
        </a>
        @if(auth()->user()->attendance)
            <a href="/lesson/answer" class="list-group-item list-group-item-action">
                Answer
            </a>
        @endif
    </div>
@endsection