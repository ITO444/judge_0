@extends('layouts.app')

@section('content')
    <a href="/admin" class="btn btn-secondary">Back</a><br/><br/>
    <h3>Lesson</h3>
    <div class="card">
        <div class="card-header btn" type="button" data-toggle="collapse" data-target="#collapse" aria-expanded="false" aria-controls="collapse">
            Sort and Filter (dummy, not implemented yet) <span class="dropdown-toggle float-right"></span>
        </div>
        <div class="collapse" id="collapse">
            <div class="card-body">
                {!! Form::open([/*'action' => '', */'method' => 'GET']) !!}
                    <h4>Sort</h4>
                    <select id="order1" name="order1" class="form-control">
                        <option value="id">Default</option>
                        <option value="username">Username</option>
                        <option value="level">Admin Level</option>
                    </select>
                    <select id="order2" name="order2" class="form-control">
                        <option value="asc">Ascending</option>
                        <option value="desc">Descending</option>
                    </select>
                    <h4>Filter</h4>
                    <select id="filter" name="filter" class="form-control">
                        <option value="all">All</option>
                        <option value="dgs">DGS</option>
                        <option value="others">Others</option>
                    </select>
                <input name="page" type="hidden" value={{'$page'}}>
                    {!!Form::submit('Go', ['class' => 'btn btn-primary'])!!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    @if(count($users) > 0)
        <br/>
        {{$users->links()}}
        <br/>
        <div class="">
            <div class="row">
                <div class="col">User</div>
                <div class="col">Answer</div>
                <div class="col">Code</div>
            </div>
            <hr/>
            @foreach($users as $user)
                <div class="row">
                    <div class="col">{{$user->name}} - {{$user->real_name}} - {{$user->display}}</div>
                    <div class="col"><pre>{{$user->answer}}</pre></div>
                    <div class="col"><pre>{{Storage::get("/usercode/$user->id/program.$language")}}</pre></div>
                </div>
                <hr/>
            @endforeach
        </div>
        {{$users->links()}}
    @else
        <p>No users attending</p>
    @endif
@endsection