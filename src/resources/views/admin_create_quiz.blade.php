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
                <div class="card-header">{{ __('Create A New Quiz') }}</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <iframe src="https://www.youtube.com/embed/ofGy1zuj2rM" width="col-md-4" height="200"></iframe>
                            <br>
                            <br>
                            <div class="form-group row">
                                <label for="video-id" class="col-md-4 col-form-label text-md-right">ID</label>
                                <div class="col-md-8">
                                    <input id="video-id" type="text" class="form-control" name="video-id" placeholder="ID ..." required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="video-link" class="col-md-4 col-form-label text-md-right">Link</label>
                                <div class="col-md-8">
                                    <input id="video-link" type="text" class="form-control" name="video-link" placeholder="YouTube Link ..." required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="video-name" class="col-md-4 col-form-label text-md-right">Name</label>
                                <div class="col-md-8">
                                    <input id="video-name" type="text" class="form-control" name="video-name" placeholder="If different from ID ..." required>
                                </div>
                            </div>

                        </div>

                        <div class="col">
                            that
                        </div>
                        <div class="col">
                            this
                        </div>
                    </div>
                    <form class="" action="index.html" method="post">
                        <div class="form-group row">

                        </div>
                    </form>
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('Create quizzes if you are an admin!') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
