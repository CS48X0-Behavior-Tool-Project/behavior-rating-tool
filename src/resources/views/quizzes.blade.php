@extends('layouts.quizzes')

@if (session('status'))
<div class="alert alert-success" role="alert">
    {{ session('status') }}
</div>
@endif

@section('content')

@if (Bouncer::is(Auth::user())->an("admin", "ta"))
<div class="row justify-content-center">
    <button class="btn btn-primary" type="button" name="button" onclick="window.location.href='/create_quiz'">Create New Quiz</button>
</div>
<br>
@endif

<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css" rel="stylesheet">

<!-- Bootstrap core JavaScript-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<!-- Page level plugin JavaScript-->
<script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>

<div class="container-fluid" style="max-width: 1200px">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">Quizzes</div>

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

            @if (session('delete-message'))
             <div class="alert alert-success">
               <strong>{{ session('delete-message') }}</strong>
             </div>
             @endif

            @if (session('edit-status'))
            <div class="alert alert-success">
                <strong>{{ session('edit-status') }}</strong>
            </div>

            @endif

            <div class="card-body">
                <!-- Computer Screens -->
                <div class="d-none d-sm-none d-md-block">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th scope="col" rowspan="2">Quiz Code</th>
                                <th scope="col" rowspan="2">Attempts</th>
                                <th scope="colgroup" colspan="2" class="text-center">Best Score</th>

                                <th scope="col" rowspan="2" style="white-space: nowrap; width: 5%">Take Quiz</th>
                                @if (Bouncer::is(Auth::user())->an("admin", "ta", "expert"))
                                  <th scope="col" rowspan="2" style="white-space: nowrap; width: 5%">Edit Quiz</th>
                                @endif
                            </tr>
                            <tr>
                                <th scope="col">Behaviours</th>
                                <th scope="col">Interpretation</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($quizzes as $quiz)
                            <tr>
                                <td>{{ $quiz->code ?? 'ERROR' }}</td>
                                <td>{{ $attempts[$quiz->id] ?? '-' }}</td>
                                <td>{{ $bestBehaviourScores[$quiz->id] ?? '-' }} / {{ $maxBehaviourScores[$quiz->id] ?? '-'}}</td>
                                <td>{{ $bestInterpretationScores[$quiz->id] ?? '-' }}</td>
                                <td><button class="btn btn-info" type="button" name="button" onclick="window.location='{{ route('quiz.show', ['id' => $quiz->id]) }}'">Take Quiz</button></td>
                                @if (Bouncer::is(Auth::user())->an("admin", "ta", "expert"))
                                <td><button class="btn btn-secondary" type="button" name="button" onclick="window.location='{{ route('edit_quiz_id_route', ['id' => $quiz->id]) }}'">Edit Quiz</button></td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- Mobile Screens -->
                <div class="d-block d-sm-block d-md-none d-lg-none d-xl-none">
                    <table class="table table-bordered" id="dataTableSmall" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Quiz Code</th>
                                <th style="white-space: nowrap; width: 5%">Attempt Information</th>
                                <th style="white-space: nowrap; width: 5%">Options</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Quiz Code</th>
                                <th>Attempt Information</th>
                                <th>Options</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach($quizzes as $quiz)
                                <tr>
                                    <td>{{ $quiz->code ?? 'ERROR' }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Attempts
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item disabled">Attempts: {{ $attempts[$quiz->id] ?? '-' }}</a>
                                                <a class="dropdown-item disabled">Best Behaviour Score: {{ $bestBehaviourScores[$quiz->id] ?? '-' }} / {{ $maxBehaviourScores[$quiz->id] ?? '-'}}</a>
                                                <a class="dropdown-item disabled">Best Interpretation Score: {{ $bestInterpretationScores[$quiz->id] ?? '-' }}</a>
                                            </div>
                                        </div>
                                    </td>
                                    <td>

                                        @if (Bouncer::is(Auth::user())->a("student"))
                                            <button class="btn btn-info" type="button" name="button" onclick="window.location='{{ route('quiz.show', ['id' => $quiz->id]) }}'">Take Quiz</button>
                                        @elseif (Bouncer::is(Auth::user())->an("admin", "ta", "expert"))
                                            <div class="dropdown">
                                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                  Quiz Options
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item" href="{{ route('quiz.show', ['id' => $quiz->id]) }}">Take Quiz</a>
                                                    <a class="dropdown-item" href="{{ route('edit_quiz_id_route', ['id' => $quiz->id]) }}">Edit Quiz</a>
                                                </div>
                                            </div>
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

@section('end-body-scripts')
    {{-- All ajax related scripts should be moved to the end-body-scripts section --}}
    <script src="{{ asset('/javascript/quizzes.js') }}"></script>
@endsection
