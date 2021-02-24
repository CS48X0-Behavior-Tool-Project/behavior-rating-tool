@extends('layouts.app')

{{-- Anytime you're using Video.js include this script for IE8 compatibility --}}
@section('view_specific_scripts')
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
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Quiz Video') }}</div>
                    <div class="card-body">

                        {{-- Be sure to include the video-wrapper div, as it's CSS components
                        will ensure that the video.js player is contained within the parent div --}}
                        <div class="video-wrapper">
                            <video id="quiz-video" class="video-js" controls="true" preload="auto" width="auto"
                                height="auto" data-setup="{'responsive': true}">
                                <source src="{{ asset('/assets/videos/' . $id . '.mp4') }}" type="video/mp4" />
                                <p class="vjs-no-js">
                                    To view this video please enable JavaScript, and consider upgrading to a
                                    web browser that
                                    <a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5
                                        video</a>
                                </p>
                            </video>
                        </div>
                        {{-- Depending on how quiz execution is handled, this video.js should be created via Javascript --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- Anytime you're using Video.js, use this CDN --}}
@section('body_end_scripts')
    <script src="https://vjs.zencdn.net/7.10.2/video.min.js"></script>
@endsection
