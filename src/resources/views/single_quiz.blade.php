@extends('layouts.quizzes')

@if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
@endif

<!-- Timer Script -->
<script type="text/javascript" src="{{ URL::asset('javascript/single_quiz.js') }}"></script>
<link rel="stylesheet" href="{{ URL::asset('css/single_quiz.css') }}">

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <!-- TODO inserts -->
                <div class="card-header">Attempting Quiz:  {{$code ?? 'QuizID'}}  {{'Attempt #' . $attempt ?? 'Attempt#'}}</div>
                <div class="card-body">

                    <form action="" method="post">
                      @csrf
                        <!-- Stopwatch -->
                        <div class="col-xs-2">
                            <p class="timer"> <span id="demo">00:00:00</span>  </p>
                        </div>

                        <!-- store starting time -->
                        <input type="hidden" id="startTime" name="startTime" value={{$time}}>

                        <!-- Video -->
                        <div class="row justify-content-center">
                            <iframe src="{{ route('videos.show', ['video' => $video]) }}" width="550" height="300" style="resize: both"></iframe>
                        </div>
                        <br>
                        <!-- Questions -->
                        <div class="row">
                            <!-- Behaviours -->
                            <div class="col">
                                <p class="title">Behaviours</p>

                                <h6 style="text-align:center;">Select all the behaviours you see in the video</h6>
                                <p style="text-align:center;">+1 Right answers</p>
                                <p style="text-align:center;">-1 Wrong answers</p>

                                <div class="form-group row justify-content-center">
                                    <div>
                                        @foreach ($options as $opt)
                                            @if ( $opt->type == "behaviour")
                                                <span>
                                                    <input type="checkbox" id="behaviour-check-{{$opt->title}}" name="behaviour-check[]" value="{{$opt->title}}"></button>
                                                    <label for="behaviour-check-{{$opt->title}}">{{$opt->title}}</label>
                                                </span>
                                                <br>
                                            @endif
                                        @endforeach

                                    </div>
                                </div>
                            </div>
                            <!-- Interpretation -->
                            <div class="col">
                                <p class="title">Interpretation</p>
                                <h6 style="text-align:center;">Select your interpretation based on the behaviours displayed</h6>
                                <p style="text-align:center;">This is either right or wrong</p>
                                <div class="form-group row justify-content-center">
                                    <div>
                                        @foreach ($options as $opt)
                                            @if ( $opt->type == "interpretation")
                                                <span id="spacing">
                                                    <input type="radio" id="interpretation-check-{{$opt->title}}" name="interpretation-check" value="{{$opt->title}}"></button>
                                                    <label for="interpretation-check-{{$opt->title}}">{{$opt->title}}</label>
                                                </span>
                                                <br>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center">
                            <button type="submit" class="btn btn-primary">
                                Submit Responses
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
