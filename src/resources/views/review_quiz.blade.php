@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Review My Quizzes') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <table>
                        <thead>
                            <tr style="border: 1px solid #f2d296;">
                                <th>Quiz Code</a></th>
                                <th>Attempted At</th>
                                <th>Time Spent</th>
                                <th>Scores</th>
                                <th>Max Scores</th>
                                <th>Interpretation Guess</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($quizzes as $data)
                            <tr style="border: 1px solid #f2d296;">    
                                <th><a href="{{ url('quizzes/review/'.$data->user_attempt_id) }}"> {{$data->quiz}}</th>
                                <th>{{$data->created_at}}</th>
                                <th>{{$data->time}}</th>
                                <th>{{$data->score}}</th>
                                <th>{{$data->max_score}}</th>
                                <th>{{$data->interpretation_guess}}</th>                  
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