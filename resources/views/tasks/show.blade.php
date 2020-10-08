@extends('layouts.app')

@section('content')
    <div class="row justify-content-center"><div class="col-md-10">
        @include("tasks.top")
        {!!$task->statement!!}
    </div></div>
@endsection