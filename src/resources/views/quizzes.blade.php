@extends('layouts.quizzes')

@if (session('status'))
<div class="alert alert-success" role="alert">
    {{ session('status') }}
</div>
@endif

<link rel="stylesheet" href="{{ URL::asset('css/quizzes.css') }}">

@section('content')

{{-- ekmu: This will likely be removed, I suspect it won't play nice with pagination --}}
<script>
    $(document).ready(function(){
      $("#myInput").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#myTable tr").filter(function() {
          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
      });
    });
</script>

<div class="container-flex mx-auto">
    <div class="row d-flex justify-content-center">
        <div class="col-md-12 col-lg-3">
            {{-- ekmu: May need to add padding for the sticky filter card. --}}
            <div class="card sticky-top">
                <div class="card-header">Filter</div>
                <div class="card-body">
                    <form action="/quizzes" method="post">
                        @csrf
                        <input class="form-control mb-3" id="myInput" type="text" placeholder="Search Quizzes..">

                        <!-- Number of attempts filter -->
                        <div class="card-header">
                            <span>Filter quizzes by your number of attempts</span>
                        </div>
                        <div class="card-body">
                            <span>
                                <input type="radio" id="attempt-all" name="attempt-radio" value="all" checked="checked">
                                <label for="attempt-all" type="text">All</label>
                            </span>
                        </div>

                        <!-- foreach, same as animals here for attempts -->
                        @foreach ($uniqueAttempts as $att)
                        <div class="card-body">
                            <span>
                                <input type="radio" id="attempt-{{ $att }}" name="attempt-radio" value="{{ $att }}">
                                <label for="attempt-{{ $att }}">{{ $att }}</label>
                            </span>
                        </div>
                        @endforeach

                        <!-- Animal filter -->
                        <div class="card-header">
                            <span>Filter quizzes by animal</span>
                        </div>
                        <div class="card-body">
                            <span>
                                <input type="radio" id="animal-all" name="animal-radio" value="all" checked="checked">
                                <label for="animal-all" type="text">All</label>
                            </span><br>

                            @foreach($animals as $data)
                            <span>
                                <input type="radio" id="animal-{{ $data }}" name="animal-radio" value="{{ $data }}">
                                <label for="animal-{{ $data }}" type="text">{{ $data }}</label><br>
                            </span>
                            @endforeach
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-12 col-lg-9">
            <div class="card">
                <div class="card-header">
                    <span>Selection
                        @if (Bouncer::is(Auth::user())->an("admin", "ta"))
                        <button class="btn btn-primary float-end" type="button" name="button" onclick="window.location.href='/create_quiz'">+ Create New Quiz</button>
                        @endif
                    </span>
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

                <!-- TODO: Insert selected filter criteria here -->
                <div class="table-responsive table-response-md table-response-lg">
                    <table class="table table-hover">
                        <thead class="table-active">
                            {{-- Headers of table --}}
                            <tr>
                                <th scope="col">Quiz code</th>
                                <th scope="col">Attempts</th>
                                <th scope="col">Best Behaviour Score</th>
                                <th scope="col">Behaviour Scores</th>
                                <th scope="col">Best Interpretation Scores</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody id="myTable">
                            {{-- TODO: Add number of attempts and best score to the quizzes button --}}
                            @foreach ($quizzes as $quiz) {{-- ekmu: Not the cleanest implementation of clickeable rows, but time constraints.. --}}
                            <tr>
                                <th scope="row">{{ $quiz->code ?? 'EMPTY' }}</th>
                                <td>{{ $attempts[$quiz->id] ?? 'EMPTY' }}</td>
                                <td>{{ $bestBehaviourScores[$quiz->id] ?? 'EMPTY' }}</td>
                                <td>{{ $maxBehaviourScores[$quiz->id] ?? 'EMPTY' }}</td>
                                <td>{{ $bestInterpretationScores[$quiz->id] ?? 'EMPTY' }}</td>
                                <td class="p-1">
                                    <button type="button" class="btn btn-primary btn-sm btn-block" name="view_button" onclick="window.location='{{ route('quiz.show', ['id' => $quiz->id]) }}'">Take Quiz</button>
                                    @if (Bouncer::is(Auth::user())->an('admin', 'ta', 'expert'))
                                    <button type="button" class="btn btn-secondary btn-sm btn-block" name="edit_button" onclick="window.location='{{ route('edit_quiz_id_route', ['id' => $quiz->id]) }}'">Edit Quiz</button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
