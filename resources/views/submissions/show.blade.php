@extends('layouts.app')

@section('content')
    <h1>Submission
    </h1>
    <br/>
    <div class="table-responsive"><table class="table table-striped table-bordered table-hover text-center text-nowrap">
        <thead><tr>
            <th>Test</th>
            <th>Result</th>
            <th>Runtime</th>
            <th>Memory</th>
            <th>Score</th>
            <th>Grader Feedback</th>
        </tr></thead><tbody>
    @foreach($submission->runs as $run)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$run->result}}</td>
            <td>{{$run->runtime}}</td>
            <td>{{$run->memory}}</td>
            <td>{{$run->score}}</td>
            <td>{{$run->grader_feedback}}</td>
        </tr>
    @endforeach
    </tbody></table></div>
    <pre>{{$submission->compiler_warning}}</pre><br/>
    <pre>{{$submission->source_code}}</pre>
@endsection