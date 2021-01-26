@extends('errors::minimal')

@section('title', __('I\'m a teapot'))
@section('code', '418')
@section('message')
<a class="btn" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
    {{__("I'm a teapot")}}
</a>
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
@endsection