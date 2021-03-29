@extends('layouts.quizzes')

@if (session('status'))
<div class="alert alert-success" role="alert">
    {{ session('status') }}
</div>
@endif

<link rel="stylesheet" href="{{ URL::asset('css/quizzes.css') }}">

@section('content')

@if (Bouncer::is(Auth::user())->an("admin", "ta"))
<div class="container container-md container-lg container-xl justify-content-center">
    <button class="btn btn-primary" type="button" name="button" onclick="window.location.href='/create_quiz'">Create New
        Quiz</button>
</div>
<br>
@endif

<div class="container container-md container-lg container-xl">
    <div class="row col-1b2">
        <div class="card col-4">
            <div class="card-header">Filter</div>

            <div class="card-body">
                <form action="/quizzes" method="post">
                    @csrf
                    <input id="search" class="form-control" type="text" name="search" placeholder="Search Quizzes..."
                        onkeyup"">

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
                            <input type="radio" id="animal-{{$data}}" name="animal-radio" value="{{$data}}">
                            <label for="animal-{{$data}}" type="text"> {{$data}} </label>
                        </span>
                    </div>
                    @endforeach

                </form>
            </div>
        </div>
        <div class="card col-8">
            <div class="card-header">
                Selection
                @if (Bouncer::is(Auth::user())->an("admin", "ta"))
                <button class="btn btn-primary float-right" type="button" name="button"
                    onclick="window.location.href='/create_quiz'">Create New
                    Quiz</button>
                @endif
            </div>

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

            <!-- TODO: Insert selected filter criteria here -->
            <div class="table-responsive table-response-md table-response-lg">
                <table class="table table-hover">
                    <thead class="table-active">
                        <tr>
                            <th scope="col">First</th>
                            <th scope="col">Second</th>
                            <th scope="col">Tres</th>
                            <th scope="col">Four</th>
                            <th scope="col">Five</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Add number of attempts and best score to the quizzes button TODO -->
                        @foreach ($quizzes as $quiz)
                        <tr>
                            <th scope="row">{{ $quiz->code ?? 'EMPTY' }}</th>
                            <td>{{ $attempts[$quiz->id] ?? 'EMPTY' }}</td>
                            <td>{{ $bestBehaviourScores[$quiz->id] ?? 'EMPTY' }}</td>
                            <td>{{ $maxBehaviourScores[$quiz->id] ?? 'EMPTY' }}</td>
                            <td>{{ $bestInterpretationScores[$quiz->id] ?? 'EMPTY' }}</td>
                        </tr>
                        {{-- ekmu: This should be replaced by a row inside a table --}}
                        {{-- <button class="btn btn-secondary" style="padding: 10px; margin: 10px;"
                            onclick="window.location.href='/quizzes/{{$quiz->id}}'">{{ $quiz->code }} </br> Attempts:
                        {{ $attempts[$quiz->id] }} </br> Best Score:
                        {{ $bestBehaviourScores[$quiz->id] ?? '-' }} / {{ $maxBehaviourScores[$quiz->id] ?? '-' }} B
                        {{ $bestInterpretationScores[$quiz->id] ?? '-'}} I
                        </button> --}}
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection