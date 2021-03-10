@extends('layouts.app')

@if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
@endif

<style media="screen">
    .title {
        text-align: center;
        color: black;
        background-color: #f7f7f7;
        border-radius: 5px;
        border: 1px solid #dfdfdf;
    }
</style>

@section('content')
<div class="container">
    <div class="row col-12">
        <div class="card col-4">
            <div class="card-header">Filter</div>

            <div class="card-body">
              <form action="/quizzes" method="post">
                @csrf
                <input id="search" class="form-control" type="text" name="search" placeholder="Search Quizzes..." onkeyup"">

                <!-- Number of attempts filter -->
                <p class="title" style="margin-top: 15px;">Filter quizzes by your number of attempts</p>
                <div class="form-group row justify-content-center">
                    <div>
                        <span>
                            <input type="radio" id="one" name="attempts-radio" value="one">
                            <label for="one"> One</label><br>
                        </span>
                        <span>
                            <input type="radio" id="two" name="attempts-radio" value="two">
                            <label for="two"> Two</label><br>
                        </span>
                        <span>
                            <input type="radio" id="three" name="attempts-radio" value="three">
                            <label for="three"> Three</label><br>
                        </span>
                        <span>
                            <input type="radio" id="four" name="attempts-radio" value="four">
                            <label for="four"> Four</label><br>
                        </span>
                        <span>
                            <input type="radio" id="five_plus" name="attempts-radio" value="five_plus">
                            <label for="five_plus"> Five +</label><br>
                        </span>
                    </div>
                </div>
                <!-- Animal filter -->
                <p class="title">Filter quizzes by animal</p>
                <div class="row justify-content-center">
                  <span>
                      <input type="radio" id="animal-all" name="animal-radio" value="all" checked="checked">
                      <label for="animal-all" type="text"> All </label>
                  </span>
                </div>
                @foreach($animals as $data)
                <div class="row justify-content-center">
                    <span>
                        <input type="radio" id="animal-{{$data}}" name="animal-radio" value = "{{$data}}">
                        <label for="animal-{{$data}}" type="text"> {{$data}} </label>
                    </span>
                </div>
                @endforeach

                </form>
            </div>
        </div>
        <div class="card col-8">
            <div class="card-header">Selection</div>
            Insert selected filter criteria here
            <!-- Insert selected filter criteria here TODO -->
            <div class="card-body">
                @foreach ($quizzes as $quiz)
                    <button class="btn btn-secondary" style="padding: 10px; margin: 10px;" onclick="window.location.href='/quizzes/{{$quiz->id}}'">{{ $quiz->code }}</button>
                @endforeach
            </div>
        </div>
    </div>
</div>

@endsection
