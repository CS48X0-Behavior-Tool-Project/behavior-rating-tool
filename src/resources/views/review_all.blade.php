@extends('layouts.review')

@if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
@endif

@section('content')

<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css" rel="stylesheet">

<!-- Bootstrap core JavaScript-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<!-- Page level plugin JavaScript-->
<script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>

<!-- Icon styles -->


<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                @if($admin_data == 'STUDENT')
                    <div class="card-header">{{ __('Review Student Quizzes: ') }} </div>
                @else
                    <div class="card-header">{{ __('Review My Quizzes') }}</div>
                @endif
                <div class="card-body">
                    <table class="table table-bordered" id="review_mine" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                @if($admin_data == 'STUDENT')
                                    <th scope="col" rowspan="2">Student</th>
                                @endif
                                <th scope="col" rowspan="2">Quiz Code</th>
                                <th scope="col" rowspan="2">Attempted At</th>
                                <th scope="col" rowspan="2">Time Spent</th>
                                <th scope="colgroup" colspan="2" class="text-center">Score</th>
                            </tr>
                            <tr>
                                <th scope="col">Behaviours</th>
                                <th scope="col">Interpretation</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($quizzes as $data)
                                <tr>
                                    @if($admin_data == 'STUDENT')
                                    <td>{{$data->first_name}} {{$data->last_name}}</td>
                                    @endif
                                    <td><a href="{{ url('review/'.$data->user_attempt_id) }}"> {{$data->quiz}}</th>
                                    <td>{{$data->created_at}}</td>
                                    <td>{{$data->time}}</td>
                                    <td>{{$data->score}} / {{$data->max_score}}</td>
                                    <td>{{$data->interpretation_guess}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <br/>
            @if (Bouncer::is(Auth::user())->an("admin", "ta") && $admin_data!='STUDENT')
            <div class="card">
                <div class="card-header">{{ __('Review All Student Quizzes') }}</div>
                <div class="card-body">
                    <div class="form-group row justify-content-center">
                        <button class="btn btn" type="button" id="add-behaviour" style="margin-right: 5px; background-color: #fc8403; color: white">
                            <i class="fas fa-plus"></i> More
                        </button>
                        <button class="btn btn-secondary" type="button" id="remove-behaviour" style="margin-left: 5px">
                            <i class="fas fa-minus"></i> Less
                        </button>
                    </div>
                    <div style="display: flex; justify-content: flex-end">
                        <!-- <strong>Download</strong>&nbsp&nbsp -->
                        <a href="{{ route('export_all_student_quizzes') }}"><strong>Download CSV</strong></a>&nbsp&nbsp
                        <a href="{{ route('export_all_student_quizzes_json') }}"><strong>JSON</strong></a>
                    </div>
                    <br>

                    <table class="table table-bordered" id="review_all" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th scope="col" rowspan="2">Student</th>
                                <th scope="col" rowspan="2">Quiz Code</th>
                                <th scope="col" rowspan="2">Number of Attempts</th>
                                <th scope="col" rowspan="2">Best Time</th>
                                <th scope="colgroup" colspan="2" class="text-center">Best Score</th>
                            </tr>
                            <tr>
                                <th scope="col">Behaviours</th>
                                <th scope="col">Interpretation</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($admin_data as $row)
                                <tr>
                                    <td><a href="{{ url('review/'.$row->id.'/all') }}"> {{$row->first_name}} {{$row->last_name}}</td>
                                    <td><a href="{{ url('review/'.'all/'.$row->code) }}"> {{$row->code}}</td>
                                    <td>{{$row->attempts}}</td>
                                    <td>{{$row->time}}</td>
                                    <td>{{$row->score}} / {{$row->max_score}}</td>
                                    <td>{{$row->interpretation_guess}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection

@section('end-body-scripts')
    {{-- All ajax related scripts should be moved to the end-body-scripts section --}}
    <script src="{{ asset('/javascript/review.js') }}"></script>
@endsection
