@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            @include("contests.top")
            @if($contest->published && $level >= $contest->reg_level && !$contest->doneBy($user))
                <div class="card">
                    <div class="card-header">Registration</div>
                    <div class="card-body">
                        {{Form::open(['action' => ['ContestsController@register', $contest->contest_id], 'method' => 'POST', 'id' => 'confirm-form'])}}
                            <div class="form-group">
                                {{Form::label('start', 'Start Time', ['class' => 'form-label'])}}
                                {{Form::input('dateTime-local', "start", Carbon\Carbon::parse($contest->start)->format("Y-m-d\Th:m:s"), ['class' => 'form-control', 'step' => '1'])}}
                            </div>
                
                            <div class="form-group">
                                <div class="form-check">
                                    {{Form::checkbox('type', true, false, ['class' => 'form-check-input', 'id' => 'type'])}}
                                    {{Form::label('type', 'Participate Unofficially', ['class' => 'form-check-label', 'for' => 'type'])}}
                                </div>
                            </div>
                            <div class="form-group">
                                {{Form::submit('Register', ['class' => 'btn btn-success confirm'])}}
                            </div>
                        {{Form::close()}}
                    </div>
                </div>
                <hr/>
            @elseif(($contest->published && $level >= $contest->reg_level && !$contest->doneBy($user)) || $contest->canUnreg($user))
                {{Form::open(['action' => ['ContestsController@unregister', $contest->contest_id], 'method' => 'delete', 'id' => 'confirm-form'])}}
                <div class="card">
                    <div class="card-header">
                        Registered
                        {{Form::submit('Unregister', ['class' => 'btn btn-sm btn-danger confirm float-right'])}}
                    </div>
                    <div class="card-body">
                            You are participating in this contest from {{$contest->participationOf($user)->start}} to {{$contest->participationOf($user)->end}}.
                    </div>
                    {{Form::close()}}
                </div>
                <hr/>
            @endif
            <h3>Information</h3>
            <div class="row">
                <div class="col-md">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <th scope="row">
                                            Status
                                        </th>
                                        <td>
                                            {{$contest->isUpcoming() ? 'Upcoming' : ($contest->isOngoing() ? 'In progress' : 'Ended')}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            Duration
                                        </th>
                                        <td>
                                            {{gmdate("G \h i \m", $contest->duration)}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            Scoring
                                        </th>
                                        <td>
                                            {{$contest->cumulative() ? 'Cumulative' : 'Last Submission'}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            Feedback
                                        </th>
                                        <td>
                                            {{$contest->feedback() ? 'Instant Feedback' : 'No Feedback'}}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <th scope="row">
                                            Official Start
                                        </th>
                                        <td class="text-center">
                                            {{$contest->start}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            Official End
                                        </th>
                                        <td class="text-center">
                                            {{$contest->end}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            Your Start
                                        </th>
                                        <td class="text-center">
                                            {{$contest->doneBy($user) ? $contest->participationOf($user)->start : '/'}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            Your End
                                        </th>
                                        <td class="text-center">
                                            {{$contest->doneBy($user) ? $contest->participationOf($user)->end : '/'}}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <hr/>
            {!!$contest->description!!}
        </div>
    </div>
@endsection
@push('scripts')
<script src="/js/dptj/form-confirm.js" type="text/javascript" charset="utf-8"></script>
@endpush