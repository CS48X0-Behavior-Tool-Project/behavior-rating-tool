@extends('layouts.quizzes')

@if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
@endif

<link rel="stylesheet" href="{{ URL::asset('css/quizzes.css') }}">

@section('content')

@if (Bouncer::is(Auth::user())->an("admin", "ta"))
    <div class="row col-12 justify-content-center">
        <button class="btn btn-primary" type="button" name="button" onclick="window.location.href='/create_quiz'">Create New Quiz</button>
    </div>
    <br>
@endif

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
                <div class="row justify-content-center">
                  <span>
                      <input type="radio" id="attempt-all" name="attempt-radio" value="all" checked="checked">
                      <label for="attempt-all" type="text"> All </label>
                  </span>
                </div>
                <!-- foreach, same as animals here for attempts -->
                @foreach ($uniqueAttempts as $att)
                <div class="row justify-content-center">
                  <span>
                      <input type="radio" id="attempt-{{$att}}" name="attempt-radio" value="{{$att}}"></button>
                      <label for="attempt-{{$att}}">{{$att}}</label>
                  </span>
                </div>
                @endforeach


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
            @if (session('score-message'))
                <div class="alert alert-success">
                    <strong>{{ session('score-message') }}</strong>
                </div>
            @endif
            @if (session('quiz-error-message'))
                <div class="alert alert-danger">
                    <strong>{{ session('quiz-error-message') }}</strong>
                </div>
            @endif
            Insert selected filter criteria here

            <!-- Insert selected filter criteria here TODO -->
            <div class="card-body">
                <!-- Add number of attempts and best score to the quizzes button TODO -->
                @foreach ($quizzes as $quiz)
                  <button class="btn btn-secondary" style="padding: 10px; margin: 10px;" onclick="window.location.href='/quizzes/{{$quiz->id}}'">{{ $quiz->code }} </br> Attempts: {{$attempts[$quiz->id]}} </br> Best Score: {{ $bestBehaviourScores[$quiz->id] ?? '-' }}/{{ $maxBehaviourScores[$quiz->id] ?? '-' }} B {{ $bestInterpretationScores[$quiz->id] ?? '-'}} I </button>
                @endforeach
            </div>
        </div>
    </div>
</div>

@endsection
