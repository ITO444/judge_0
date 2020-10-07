@extends('layouts.app')

@section('content')
    @include("tasks.top")
    <div class="row justify-content-center"><div class="col-md-10">
        {!!$task->solution!!}
    </div></div>
@endsection