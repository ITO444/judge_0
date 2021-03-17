@extends('layouts.app')

@section('pageTitle', 'Images')

@section('content')
    <h3>Images</h3>
    <br/>
    @if(count($images) > 0)
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead><tr>
                <th>Image</th>
                <th>Name</th>
                <th>Location</th>
                <th>Actions</th>
            </tr></thead><tbody>
        @foreach($images as $image)
            <tr>
                <td><img src="/storage/images/{{$image->name}}.{{$image->extension}}" class="img-fluid"/></td>
                <td>{{$image->name}}</td>
                <td><span class="io">/storage/images/{{$image->name}}.{{$image->extension}}</div></td>
                <td>
                    <a href="/admin/images/{{$image->name}}" class="btn btn-primary">Edit</a>
                </td>
            </tr>
            @if($image->name == $imageChange)
        </tbody></table><hr/>
            <div class="card">
                <h3 class="card-header text-center" type="button" data-toggle="collapse" data-target="#collapse" aria-expanded="{{session('success')?'false':'true'}}" aria-controls="collapse">
                    Change image {{$image->name}} <span class="dropdown-toggle float-right"></span>
                </h3>
                <div class="collapse {{session('success')?'':'show'}}" id="collapse"><div class="card-body">
                    {{Form::open(['action' => ['AdminController@saveImage', $image->name], 'files' => 'true'])}}
                    <div class="row">
                        <div class="col-lg">
                            <img class="img-fluid" src="/storage/images/{{$image->name}}.{{$image->extension}}"/>
                        </div>
                        <div class="col-lg">
                            <div class='form-group'>
                                {{Form::label('name', 'Name', ['class' => 'form-label'])}}
                                {{Form::text('name', $image->name, ['class' => 'form-control'])}}
                            </div>
                            <div class='form-group'>
                                {{Form::label('description', 'Description', ['class' => 'form-label'])}}
                                {{Form::textarea('description', $image->description, ['class' => 'form-control'])}}
                            </div>
                            <div class='form-group'>
                                {{Form::label('image', 'Upload image', ['class' => 'form-label'])}}
                                {{Form::file('image', ['class' => 'form-control-file'])}}
                            </div>
                            {{Form::submit('Save', ['class' => 'btn btn-primary'])}}
                        </div>
                    </div>
                    {{Form::close()}}
                </div></div>
            </div><hr/>
        <table class="table table-striped table-bordered table-hover text-center text-nowrap">
            <thead><tr>
                <th>Image</th>
                <th>Name</th>
                <th>Location</th>
                <th>Actions</th>
            </tr></thead><tbody>
            @endif
        @endforeach
        </tbody></table>
    </div>
    @else
        <p>No images found</p>
    @endif
    <div class="card">
        <h3 class="card-header text-center">Add image</h3>
        <div class="card-body"><div class="row justify-content-center"><div class="col-md-8">
            {{Form::open(['action' => ['AdminController@saveImage'], 'files' => 'true'])}}
            <div class='form-group'>
                {{Form::label('name', 'Name', ['class' => 'form-label'])}}
                {{Form::text('name', '', ['class' => 'form-control'])}}
            </div>
            <div class='form-group'>
                {{Form::label('description', 'Description', ['class' => 'form-label'])}}
                {{Form::textarea('description', '', ['class' => 'form-control'])}}
            </div>
            <div class='form-group'>
                {{Form::label('image', 'Upload image', ['class' => 'form-label'])}}
                {{Form::file('image', ['class' => 'form-control-file'])}}
            </div>
            {{Form::submit('Save', ['class' => 'btn btn-primary'])}}
            {{Form::close()}}
        </div></div></div>
    </div>
@endsection