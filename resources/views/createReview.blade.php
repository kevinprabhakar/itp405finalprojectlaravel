@extends('layout')

@section('title','Create Review')

@section('main')

<div class="row">
    <div class="col">
        <h2>{{$recipe->Title}} Review</h2>
        <form action="/reviews/new/{{$recipe->id}}" method="post">
            @csrf
            <div class="form-group">
                <label for="Title">Title</label>
                <input type="text" name="Title" id="Title" class="form-control" value="{{old('Title')}}">
                <small class="text-danger">{{$errors->first('Title')}}</small>

                <label for="Rating">Select Rating:</label>
                <select class="form-control" name="Rating" id="Rating">
                      <option value="1">1</option>
                      <option value="2">2</option>
                      <option value="3">3</option>
                      <option value="4">4</option>
                      <option value="5">5</option>
                </select>
                <small class="text-danger">{{$errors->first('Rating')}}</small>


                <label for="Body">Review Body</label>
                <textarea class="form-control" name="Body" id="Body" rows="3" value="{{old('Body')}}"></textarea>
                <small class="text-danger">{{$errors->first('Body')}}</small>

            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>

@endsection
