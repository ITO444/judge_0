@extends('layouts.app')

@section('pageTitle', "$contest->contest_id - $contest->name")

@section('content')
    <div class="row justify-content-center"><div class="col-md-10">
        @include("contests.top")
        {!!$contest->editorial!!}
    </div></div>
@endsection