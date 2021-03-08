@extends('layouts.app')

@if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
@endif

<!-- Timer Script -->
<script type="text/javascript" src="{{ URL::asset('javascript/single_quiz.js') }}"></script>

<style>
    .title {
        text-align: center;
        color: black;
        background-color: #f7f7f7;
        border-radius: 5px;
        border: 1px solid #dfdfdf;
    }

    .timer {
        text-align: right;
    }
    p span {
        color: black;
        background-color: #f7f7f7;
        border-radius: 5px;
        padding: 10px;
        border: 1px solid #dfdfdf;
    }
</style>

{{-- Anytime you're using Video.js include this script for IE8 compatibility --}}
@section('view_specific_scripts')
    <script src="https://vjs.zencdn.net/7.10.2/video.min.js"></script>
    <script src="https://vjs.zencdn.net/ie8/1.1.2/videojs-ie8.min.js"></script>
@endsection

{{-- Anytime you're using Video.js these stylesheets --}}
@section('view_specific_styles')
    <link href="https://vjs.zencdn.net/7.10.2/video-js.css" rel="stylesheet" />
    <link href="{{ asset('/css/styles.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <!-- TODO inserts -->
                <div class="card-header">Attempting Quiz:  {{$code ?? 'QuizID'}}  {{$attempt ?? 'Attempt#'}}</div>
                <div class="card-body">

                    <form action="" method="post">
                      @csrf
                        <!-- Stopwatch -->
                        <div class="col-xs-2">
                            <p class="timer"> <span id="demo">00:00:00</span>  </p>
                        </div>

                        <!-- Video -->
                        {{-- Be sure to include the video-wrapper div, as it's CSS components
                        will ensure that the video.js player is contained within the parent div --}}
                        <div class="video-wrapper">
                            <video id="quiz-video" class="video-js vjs-show-big-play-button-on-pause" controls preload="true"
                                data-setup="{'responsive': true, 'fluid': true, 'aspectRatio': '16:9', 'fill': true}">
                                <source src="{{ route('videos.show', ['video' => $video]) }}" type="video/mp4" />
                                <p class="vjs-no-js">
                                    To view this video please enable JavaScript, and consider upgrading to a
                                    web browser that
                                    <a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5
                                        video</a>
                                </p>
                            </video>
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
