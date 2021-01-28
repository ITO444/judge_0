
@if($contest->published)
    @if($contest->isOngoing())
        <div class="alert alert-warning">You may not unpublish an ongoing contest</div>
    @else
        <div class="alert alert-warning">Please <a href="/contest/{{$contest->contest_id}}/unpublish" class="alert-link">unpublish</a> to edit this contest</div>
    @endif
    @elseif($level >= 6)
    <div class="alert alert-primary">Please <a href="/contest/{{$contest->contest_id}}/publish" class="alert-link">publish</a> this contest to enable registration</div>
@endif