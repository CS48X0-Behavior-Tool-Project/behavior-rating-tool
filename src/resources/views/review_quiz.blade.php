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
                    <table style="width: 100%;">
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
                                <td><a href="{{ url('quizzes/review/'.$data->user_attempt_id) }}"> {{$data->quiz}}</th>
                                <td>{{$data->created_at}}</td>
                                <td>{{$data->time}}</td>
                                <td>{{$data->score}}</td>
                                <td>{{$data->max_score}}</td>
                                <td>{{$data->interpretation_guess}}</td>                  
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <br/>
            @if (Bouncer::is(Auth::user())->an("admin", "ta"))
            <div class="card">
                <div class="card-header">{{ __('Review All Student Quizzes') }}</div>
                <div class="card-body">
                    <table style="width: 100%;">
                        <thead>
                            <tr style="border: 1px solid #f2d296;">
                                <th>Student</a></th>
                                <th>Quiz Code</th>
                                <th>Attempt Numbers</th>
                                <th>Best Scores</th>
                                <th>Time spent</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($admin_data as $row)
                            <tr style="border: 1px solid #f2d296;">    
                                <td>{{$row->email}}</td>
                                <td>{{$row->code}}</td>
                                <td>{{$row->attempts}}</td>
                                <td>{{$row->score}}</td>
                                <td>{{$row->time}}</td>                  
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div style="display: flex; justify-content: flex-end">
                        <a href="{{ route('export_all_student_quizzes') }}"><strong>Download</strong></a>
                    </div>
                    
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection