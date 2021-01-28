@extends('errors::minimal')

@section('title', __('I\'m a teapot'))
@section('code', '418')
@section('message')
<a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
    {{__("I'm a teapot")}}
</a>
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
@if(auth()->user()->temp_level < auth()->user()->getRawOriginal('level'))
    <a href="/admin/reset_temp_level">
        <small>(Click to revert to user level {{auth()->user()->getRawOriginal("level")}})</small>
    </a>
@endif
@endsection