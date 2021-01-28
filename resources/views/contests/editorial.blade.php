@extends('layouts.app')

@section('content')
    <div class="row justify-content-center"><div class="col-md-10">
        @include("contests.top")
        {!!$contest->editorial!!}
    </div></div>
@endsection