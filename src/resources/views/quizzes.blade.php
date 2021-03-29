@extends('layouts.quizzes')

@if (session('status'))
<div class="alert alert-success" role="alert">
    {{ session('status') }}
</div>
@endif

<link rel="stylesheet" href="{{ URL::asset('css/quizzes.css') }}">

@section('content')

<div class="container-fluid flex-row">
    <div class="row">
        <div class="card col-2">
            <div class="card-header container-fluid">Filter</div>

            <div class="card-body">
                <form action="/quizzes" method="post">
                    @csrf
                    <input id="search" class="form-control" type="text" name="search" placeholder="Search Quizzes..." onkeyup"">

                    <!-- Number of attempts filter -->
                    <p class="title mb-2">Filter quizzes by your number of attempts</p>
                    <div class="row mb-2 justify-content-center">
                        <span>
                            <input type="radio" id="attempt-all" name="attempt-radio" value="all" checked="checked">
                            <label for="attempt-all" type="text"> All </label>
                        </span>
                    </div>
                    <!-- foreach, same as animals here for attempts -->
                    @foreach ($uniqueAttempts as $att)
                    <div class="row justify-content-center">
                        <span>
                            <input type="radio" id="attempt-{{ $att }}" name="attempt-radio" value="{{ $att }}"></button>
                            <label for="attempt-{{ $att }}">{{ $att }}</label>
                        </span>
                    </div>
                    @endforeach


                    <!-- Animal filter -->
                    <p class="title">Filter quizzes by animal</p>
                    <div class="row justify-content-center">
                        <span>
                            <input type="radio" id="animal-all" name="animal-radio" value="all" checked="checked">
                            <label for="animal-all" type="text">All</label>
                        </span>
                    </div>
                    @foreach($animals as $data)
                    <div class="d-flex justify-content-center">
                        <span>
                            <input type="radio" id="animal-{{ $data }}" name="animal-radio" value="{{ $data }}">
                            <label for="animal-{{ $data }}" type="text">{{ $data }}</label>
                        </span>
                    </div>
                    @endforeach

                </form>
            </div>
        </div>
        <div class="card col-10">
            <div class="card-header"> Selection
                @if (Bouncer::is(Auth::user())->an("admin", "ta"))
                <button class="btn btn-primary float-right" type="button" name="button" onclick="window.location.href='/create_quiz'">Create New Quiz</button>
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
                        <tr onclick="window.location='{{ route('quiz.show', ['id' => $quiz->id]) }}'">
                            <th scope="row">{{ $quiz->code ?? 'EMPTY' }}</th>
                            <td>{{ $attempts[$quiz->id] ?? 'EMPTY' }}</td>
                            <td>{{ $bestBehaviourScores[$quiz->id] ?? 'EMPTY' }}</td>
                            <td>{{ $maxBehaviourScores[$quiz->id] ?? 'EMPTY' }}</td>
                            <td>{{ $bestInterpretationScores[$quiz->id] ?? 'EMPTY' }}</td>
                        </tr>
                        </button>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection