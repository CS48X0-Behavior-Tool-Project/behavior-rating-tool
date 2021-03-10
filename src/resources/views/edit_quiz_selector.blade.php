@extends('layouts.app')

@if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
@endif

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Select a Quiz to Edit') }}</div>

                <div class="card-body">
                    <div class="input-group mb-3">
                        <select class="custom-select" id="inputGroupSelect02">
                            <option selected>Quizzes...</option>
                            <!-- for each quiz in the database make an option -->
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                        <div class="input-group-append">
                            <!-- TODO href should take you to edit_quiz_by_id{selectedQuiz} -->
                            <a class="input-group-text btn btn-primary" for="inputGroupSelect02" href="{{url('/edit_quiz')}}">Edit!</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
