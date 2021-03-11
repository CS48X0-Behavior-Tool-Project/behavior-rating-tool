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
                            @foreach($quizzes as $quiz)
                                <option value="{{$quiz->id}}">{{$quiz->code}}</option>
                            @endforeach
                        </select>

                        <script>
                            function selectQuiz() {
                                var val = document.getElementById("inputGroupSelect02").value;
                                window.location.href = `/edit_quiz/${val}`;
                            }
                        </script>

                        <div class="input-group-append">
                            <a class="input-group-text btn btn-primary" for="inputGroupSelect02" href="javascript:selectQuiz()">Edit!</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
