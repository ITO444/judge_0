@extends('layouts.app')

@section('content')
    @include("tasks.top")
    <div class="row justify-content-center"><div class="col-md-10">
        {!!$task->statement!!}
    </div></div>
@endsection