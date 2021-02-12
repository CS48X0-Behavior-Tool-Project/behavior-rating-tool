@extends('layouts.app')

@section('view_specific_scripts')
    <script src="https://vjs.zencdn.net/ie8/1.1.2/videojs-ie8.min.js"></script>
@endsection

@section('view_specific_styles')
    <link href="https://vjs.zencdn.net/7.10.2/video-js.css" rel="stylesheet" />
    <link href="{{ asset('/css/styles.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Attempting Quiz') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        {{ __('Attempt a quiz!') }}
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Quiz Video') }}</div>

                    <div class="card-body">
                        @if (file_exists(public_path('/assets/videos/' . $id . '.mp4')))
                            <!-- TODO: Implement quiz functionality -->
                            <!-- TODO: Implement styling for video -->
                            <div class="video-wrapper">
                                <video id="quiz-video" class="video-js" controls preload="auto" width="auto" height="auto"
                                    data-setup="{'responsive': true}">
                                    <source src="{{ asset('/assets/videos/' . $id . '.mp4') }}" type="video/mp4" />
                                    <p class="vjs-no-js">
                                        To view this video please enable JavaScript, and consider upgrading to a
                                        web browser that
                                        <a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5
                                            video</a>
                                    </p>
                                </video>
                            </div>
                        @else
                            <div class="alert alert-danger">
                                <strong>Quiz video could not be located.</strong>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('body_end_scripts')
    <script src="https://vjs.zencdn.net/7.10.2/video.min.js"></script>
@endsection
