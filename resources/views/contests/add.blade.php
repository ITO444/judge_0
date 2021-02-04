@extends('layouts.app')

@section('content')
    @include("contests.top")
    @include("contests.publish_warning")
    <h1>Manage Contestants</h1>
    <hr/>
    <div class="card">
        <div class="card-header">Add Contestant</div>
        <div class="card-body">
            {{Form::open(['action' => ['ContestsController@addContestant', $contest->contest_id], 'method' => 'POST'])}}
                <div class="form-group">
                    {{Form::label('name', 'Username', ['class' => 'form-label'])}}
                    {{Form::text('name', '', ['class' => 'form-control'])}}
                </div>

                <div class="form-group">
                    {{Form::label('start', 'Start Time', ['class' => 'form-label'])}}
                    {{Form::input('dateTime-local', "start", Carbon\Carbon::parse($contest->start)->format("Y-m-d\TH:i:s"), ['class' => 'form-control', 'step' => '1'])}}
                </div>
    
                <div class="form-group">
                    <div class="form-check">
                        {{Form::checkbox('type', true, false, ['class' => 'form-check-input', 'id' => 'type'])}}
                        {{Form::label('type', 'Unofficial Participant', ['class' => 'form-check-label', 'for' => 'type'])}}
                    </div>
                </div>
                <div class="form-group">
                    {{Form::submit('Add', ['class' => 'btn btn-success'])}}
                </div>
            {{Form::close()}}
        </div>
    </div>
    <hr/>
    <h3>All Contestants</h3>
    @if(count($contest->participations) > 0)
        <div class="table-responsive"><table class="table table-striped table-bordered table-hover text-nowrap">
            <thead><tr>
                <th class="text-center">Time</th>
                <th>Contestant</th>
                <th class="text-center">Type</th>
                <th class="text-center">Status</th>
                <th class="text-center">Score</th>
                <th class="text-center">Remove</th>
            </tr></thead>
            <tbody>
            @foreach($contest->participations as $participation)
                <tr>
                    <td class="text-center">
                        {{$participation->start}} - {{$participation->end}}
                    </td>
                    <td>
                        {{$participation->user->name}} - {{$participation->user->display}}
                    </td>
                    <td class="text-center">
                        {{$participation->type ? "Official" : "Unofficial"}}
                    </td>
                    <td class="text-center">
                        {{$participation->isUpcoming() ? 'Upcoming' : ($participation->isOngoing() ? 'In progress' : 'Ended')}}
                    </td>
                    <td class="text-center">
                        {{$participation->isUpcoming() ? '' : $participation->score / 1000}}
                    </td>
                    <td class="text-center">
                        <a data-id="{{$participation->id}}" class="btn btn-sm btn-danger del"><span aria-hidden="true">&times;</span></a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table></div>
        {{Form::open(['method' => 'delete', 'id' => "delete"])}} {{Form::close()}}
    @else
        <p>No contestants at all</p>
    @endif
@endsection

@push('scripts')
<script>
    var path = "/contest/{{$contest->contest_id}}/edit/contestants/";
</script>
<script src="/js/dptj/delete-id.js" type="text/javascript" charset="utf-8"></script>
@endpush