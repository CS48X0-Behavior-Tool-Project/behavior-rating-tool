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

<div class="container">
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

            <div class="d-none d-sm-none d-md-block">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th scope="col" rowspan="2">Quiz Code</th>
                            <th scope="col" rowspan="2">Attempts</th>
                            <th scope="colgroup" colspan="2" class="text-center">Best Score</th>
                            <th scope="col" rowspan="2">Take Quiz</th>
                            @if (Bouncer::is(Auth::user())->an("admin", "ta"))
                              <th scope="col" rowspan="2">Edit Quiz</th>
                            @endif
                        </tr>
                        <tr>
                          <th scope="col">Behaviours</th>
                          <th scope="col">Interpretation</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th scope="col" rowspan="2">Quiz Code</th>
                            <th scope="col" rowspan="2">Attempts</th>
                            <th scope="colgroup" colspan="2" class="text-center">Best Score</th>
                            <th scope="col" rowspan="2">Take Quiz</th>
                            @if (Bouncer::is(Auth::user())->an("admin", "ta"))
                              <th scope="col" rowspan="2">Edit Quiz</th>
                            @endif
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($quizzes as $quiz)
                        <tr>
                            <th scope="row">{{ $quiz->code ?? 'ERROR' }}</th>
                            <td>{{ $attempts[$quiz->id] ?? '-' }}</td>
                            <td>{{ $bestBehaviourScores[$quiz->id] ?? '-' }} / {{ $maxBehaviourScores[$quiz->id] ?? '-'}}</td>
                            <td>{{ $bestInterpretationScores[$quiz->id] ?? '-' }}</td>
                            <td>Take Quiz</td>
                            @if (Bouncer::is(Auth::user())->an('admin', 'ta'))
                            <td>Edit Quiz</td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@section('end-body-scripts')
    {{-- All ajax related scripts should be moved to the end-body-scripts section --}}
    <script src="{{ asset('/javascript/quizzes.js') }}"></script>
@endsection
