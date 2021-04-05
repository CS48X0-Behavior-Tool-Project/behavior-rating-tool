@extends('layouts.quizzes')

@if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
@endif

<link rel="stylesheet" href="{{ URL::asset('css/admin_create_quiz.css') }}">

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header"> Edit Quiz {{$quiz->code}} </div>
                    @if (session('quiz-status'))
                        <div class="alert alert-success">
                            <strong>{{ session('quiz-status') }}</strong>
                        </div>
                    @endif
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <div class="col-sm-12 col-md-6 col-lg-4">
                                <video id="thumbnail" class="" width="100%" style="max-width: 100%" controls>
                                    <source src="{{ route('videos.show', ['video' => $quiz->video]) }}" type="video/mp4"/>
                                </video>
                                <br>
                                <br>
                                <p class="title" id="import-video">Import Video</p>
                                <div>
                                    <form action="{{ route('videos.store') }}" id="upload-form" method="post" enctype="multipart/form-data"> @csrf
                                        <div class="custom-file" >
                                            <div class=" row justify-content-center">
                                                <input type="file" class="custom-file-input" name="video" id="video-upload" accept="video/mp4,video/mpeg,video/x-matroska" onchange="updateVideoLabel();">
                                                <label class="custom-file-label" for="video" id="file-label" style="white-space: nowrap; text-overflow: ellipsis; overflow: hidden;">Choose file</label>
                                            </div>
                                        </div>
                                        <br>
                                        <br>
                                        <div class="row justify-content-center">
                                            <button class="btn btn-secondary" id="upload-button">Upload</button>
                                        </div>
                                        <br>

                                        @if (session('video-status'))
                                            <div class="alert alert-danger">
                                                <strong>{{ session('video-status') }}</strong>
                                            </div>
                                        @endif
                                    </form>
                                </div>
                                <div class="modal"></div>
                                <div style="display:none" id="upload-alert" role="alert"></div>
                                <br>

                                <form action="/edit_quiz/{{$quiz->id}}" method="post">
                                @csrf
                                <p class="title">Video information</p>
                                <div class="form-group row">
                                    <label for="video-id" class="col-md-3 col-form-label text-md-right">ID</label>
                                    <div class="col-md-9">
                                        <input id="video-id" type="text" class="form-control" name="video-id" value="{{$quiz->video}}" readonly="readonly">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="name-id" class="col-md-3 col-form-label text-md-right">Name</label>
                                    <div class="col-md-9">
                                        <input id="video-name" type="text" class="form-control" name="video-name" value="{{$quiz->code}}">
                                    </div>
                                </div>

                                <p class="title" id="animal-info">Animal information</p>
                                <p style="text-align:center;"> This will be autogenerated to show all possible animals to choose from </p>
                                <p style="text-align:center;"> Or allow to enter a new animal if it isn't an option </p>
                                <div class="form-group row justify-content-center">
                                    <div>
                                        @foreach($animals as $data)
                                            @if ($data == $quiz->animal)
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <input type="radio" id="a-{{$data}}" name="animal-radio[]" value="{{$data}}" checked="true">
                                                    </div>
                                                </div>
                                                <label for="a-{{$data}}" id="a-{{$data}}" type="text" class="form-control" name="a-{{$data}}"> {{$data}} </label>
                                            </div>
                                            @else
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <input type="radio" id="a-{{$data}}" name="animal-radio[]" value="{{$data}}">
                                                        </div>
                                                    </div>
                                                    <label for="a-{{$data}}" id="a-{{$data}}" type="text" class="form-control" name="a-{{$data}}"> {{$data}} </label>
                                                </div>
                                            @endif
                                        @endforeach
                                        <div class="row-justify-content-center">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <input type="radio" id="a-new" name="animal-radio[]" value="New">
                                                    </div>
                                                </div>
                                                <input id="animal-new" type="text" class="form-control" name="a-new" placeholder="Enter new animal ...">
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    @if (session('animal-status'))
                                        <div class="alert alert-danger">
                                            <strong>{{ session('animal-status') }}</strong>
                                        </div>
                                    @endif
                                </div>

                            </div>
                            <div class="col-sm-12 col-md-3 col-lg-4">
                                <p class="title" id="behaviour-info">Behaviours</p>
                                <h6 style="text-align:center;">Enter all the behaviours to included in the quiz</h6>
                                <p style="text-align:center;">Fields left "Edit me..." or blank will not be included in the quiz</p>
                                <h6 style="text-align:center;">Check the correct answers</h6>
                                <div class="form-group row justify-content-center">
                                    <div>
                                        <!-- For each possible behaviour sent forward -->
                                        @foreach (range(0,9) as $x)
                                            <!-- If it exists and is correct -->
                                            @if (@isset($b_options[$x]) and $b_options[$x]->is_solution)
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <input type="checkbox" id="b-{{$x}}" name="behaviour-check[]" checked="true" value="{{$x}}">
                                                        </div>
                                                    </div>
                                                    <input id="box-{{$x}}" type="text" class="form-control" name="box-{{$x}}" placeholder="Edit me..." value="{{$b_options[$x]->title}}">
                                                </div>
                                            <!-- If it exists and is NOT correct -->
                                            @elseif (@isset($b_options[$x]))
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <input type="checkbox" id="b-{{$x}}" name="behaviour-check[]" value="{{$x}}">
                                                        </div>
                                                    </div>
                                                    <input id="box-{{$x}}" type="text" class="form-control" name="box-{{$x}}" placeholder="Edit me..." value="{{$b_options[$x]->title}}">
                                                </div>
                                            <!-- Doesn't exist -->
                                            @else
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <input type="checkbox" id="b-{{$x}}" name="behaviour-check[]" value="{{$x}}">
                                                        </div>
                                                    </div>
                                                    <input id="box-{{$x}}" type="text" class="form-control" name="box-{{$x}}" placeholder="Edit me...">
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                @if (session('behaviour-status'))
                                    <div class="alert alert-danger">
                                        <strong>{{ session('behaviour-status') }}</strong>
                                    </div>
                                @endif
                            </div>
                            <div class="col-sm-12 col-md-3 col-lg-4">
                                <p class="title" id="interpretation-info">Interpretation</p>
                                <h6 style="text-align:center;">Enter all the interpretations to included in the quiz</h6>
                                <p style="text-align:center;">Fields left "Edit me..." or blank will not be included in the quiz</p>
                                <h6 style="text-align:center;">Select the correct answers</h6>
                                <div class="form-group row justify-content-center">
                                    <div>
                                        <!-- For each possible interpretation sent forward -->
                                        @foreach (range(0,4) as $x)
                                            <!-- If it exists and is correct -->
                                            @if (@isset($i_options[$x]) and $i_options[$x]->is_solution)
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <input type="radio" id="i-{{$x}}" name="interpretation-radio" checked="true" value="{{$x}}">
                                                        </div>
                                                    </div>
                                                    <input id="inter-{{$x}}" type="text" class="form-control" name="inter-{{$x}}" placeholder="Edit me ..." value="{{$i_options[$x]->title}}">
                                                </div>
                                            <!-- If it exists and is NOT correct -->
                                            @elseif (@isset($i_options[$x]))
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <input type="radio" id="i-{{$x}}" name="interpretation-radio" value="{{$x}}">
                                                        </div>
                                                    </div>
                                                    <input id="inter-{{$x}}" type="text" class="form-control" name="inter-{{$x}}" placeholder="Edit me ..." value="{{$i_options[$x]->title}}">
                                                </div>
                                            @else
                                            <!-- Doesn't exist -->
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <input type="radio" id="i-{{$x}}" name="interpretation-radio" value="{{$x}}">
                                                        </div>
                                                    </div>
                                                    <input id="inter-{{$x}}" type="text" class="form-control" name="inter-{{$x}}" placeholder="Edit me ...">
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                @if (session('int-status'))
                                    <div class="alert alert-danger">
                                        <strong>{{ session('int-status') }}</strong>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="row justify-content-center">
                            <button type="submit" class="btn btn-primary" onclick="validate(event)">
                                Edit Quiz
                            </button>
                        </div>

                      </form>


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('end-body-scripts')
    {{-- All ajax related scripts should be moved to the end-body-scripts section --}}
    <script src="{{ asset('/javascript/create_quiz.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('javascript/edit_quiz.js') }}"></script>
@endsection
