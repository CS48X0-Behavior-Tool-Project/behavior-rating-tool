@extends('layouts.app')

@if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
@endif

<script type="text/javascript" src="{{ URL::asset('javascript/edit_quiz.js') }}"></script>

<style>
    .formLabel {
        text-align: center;
        color: black;
        border-radius: 5px;
        border: 1px solid #dfdfdf;
        min-width: 75px;
        min-height: 30px;
        max-width: 200px;
    }

    .title {
        text-align: center;
        color: black;
        background-color: #f7f7f7;
        border-radius: 5px;
        border: 1px solid #dfdfdf;
    }

    #spacing {
        margin-bottom: 10px;
        display: block;
    }

</style>

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

                  <!-- display data for testing purposes-->
                    <table border = "1">
                    <tr>
                    <td>Quiz Id</td>
                    <td>Code</td>
                    <td>Animal</td>
                    <td>Video</td>
                    </tr>
                    <tr>
                    <td>{{ $quiz->id }}</td>
                    <td>{{ $quiz->code }}</td>
                    <td>{{ $quiz->animal }}</td>
                    <td>{{ $quiz->video }}</td>
                    </tr>
                    </table>

                    <br><br>

                    <table border = "1">
                    <tr>
                    <td>Quiz Id</td>
                    <td>Type</td>
                    <td>Title</td>
                    <td>Marking Scheme</td>
                    <td>Is Solution</td>
                    </tr>
                    @foreach ($options as $opt)
                    <tr>
                    <td>{{ $opt->quiz_id }}</td>
                    <td>{{ $opt->type }}</td>
                    <td>{{ $opt->title }}</td>
                    <td>{{ $opt->marking_scheme }}</td>
                    <td>{{ $opt->is_solution }}</td>
                    </tr>
                    @endforeach
                    </table>

                    <div class="card-body">
                        <div class="row justify-content-center">
                            <div class="col">
                                <iframe id="thumbnail" src="" width="col-md-4" height="200"></iframe>
                                <br>
                                <br>
                                <p class="title" id="import-video">Import Video</p>
                                <div>
                                    <form action="{{ route('videos.store') }}" id="upload-form" method="post" enctype="multipart/form-data"> @csrf
                                        <div class="custom-file" >
                                            <div class=" row justify-content-center">
                                                <input type="file" class="custom-file-input" name="video" id="video-upload" accept="video/*" onchange="updateVideoLabel();">
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
                                <div style="display:none" id="upload-alert" role="alert">
                                </div>
                                <br>

                                <form action="/create_quiz" method="post">
                                @csrf
                                <p class="title">Video information</p>
                                <div class="form-group row">
                                    <label for="video-id" class="col-md-3 col-form-label text-md-right">ID</label>
                                    <div class="col-md-9">
                                        <input id="video-id" type="text" class="form-control" name="video-id" placeholder="Autogenerated ID" disabled="disabled">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="name-id" class="col-md-3 col-form-label text-md-right">Name</label>
                                    <div class="col-md-9">
                                        <input id="video-name" type="text" class="form-control" name="video-name" placeholder="Quiz Name">
                                    </div>
                                </div>

                                <p class="title" id="animal-info">Animal information</p>
                                <p style="text-align:center;"> This will be autogenerated to show all possible animals to choose from </p>
                                <p style="text-align:center;"> Or allow to enter a new animal if it isn't an option </p>
                                <div class="form-group row justify-content-center">
                                    <div>
                                        @foreach($animals as $data)
                                        <!-- TODO this is only return COW when quizzes returns COW nad HORSE -->
                                        <div class="row-justify-content-center">
                                            <span id="spacing">
                                                <input type="radio" id="a-{{$data}}" name="animal-radio[]" value = "{{$data}}">
                                                <label for="a-{{$data}}" type="text" name="a-{{$data}}"> {{$data}} </label>
                                            </span>
                                        </div>
                                        @endforeach
                                        <div class="row-justify-content-center">
                                            <span id="spacing">
                                                <input type="radio" id="a-new" name="animal-radio[]" value = "New">
                                                <!-- TODO IMPORTANT ZAK I changed this id to be animal-new instead of a-new so I can tell the difference between the
                                                      radio button and the input field -->
                                                <input id="animal-new" type="text" class="formLabel" name="a-new" placeholder="Edit me ...">
                                            </span>
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
                            <div class="col">
                                <p class="title" id="behaviour-info">Behaviours</p>
                                <h6 style="text-align:center;">Enter all the behaviours to included in the quiz</h6>
                                <p style="text-align:center;">Fields left "Edit me..." or blank will not be included in the quiz</p>
                                <h6 style="text-align:center;">Check the correct answers</h6>
                                <div class="form-group row justify-content-center">
                                    <div>
                                        <span id="spacing">
                                            <input type="checkbox" id="b-one" name="behaviour-check[]">
                                            <input id="box-one" type="text" class="formLabel" name="box-one" placeholder="Edit me ...">
                                        </span>
                                        <span id="spacing">
                                            <input type="checkbox" id="b-two" name="behaviour-check[]">
                                            <input id="box-two" type="text" class="formLabel" name="box-two" placeholder="Edit me ...">
                                        </span>
                                        <span id="spacing">
                                            <input type="checkbox" id="b-three" name="behaviour-check[]">
                                            <input id="box-three" type="text" class="formLabel" name="box-three" placeholder="Edit me ...">
                                        </span>
                                        <span id="spacing">
                                            <input type="checkbox" id="b-four" name="behaviour-check[]">
                                            <input id="box-four" type="text" class="formLabel" name="box-four" placeholder="Edit me ...">
                                        </span>
                                        <span id="spacing">
                                            <input type="checkbox" id="b-five" name="behaviour-check[]">
                                            <input id="box-five" type="text" class="formLabel" name="box-five" placeholder="Edit me ...">
                                        </span>
                                        <span id="spacing">
                                            <input type="checkbox" id="b-six" name="behaviour-check[]">
                                            <input id="box-six" type="text" class="formLabel" name="box-six" placeholder="Edit me ...">
                                        </span>
                                        <span id="spacing">
                                            <input type="checkbox" id="b-seven" name="behaviour-check[]">
                                            <input id="box-seven" type="text" class="formLabel" name="box-seven" placeholder="Edit me ...">
                                        </span>
                                        <span id="spacing">
                                            <input type="checkbox" id="b-eight" name="behaviour-check[]">
                                            <input id="box-eight" type="text" class="formLabel" name="box-eight" placeholder="Edit me ...">
                                        </span>
                                        <span id="spacing">
                                            <input type="checkbox" id="b-nine" name="behaviour-check[]">
                                            <input id="box-nine" type="text" class="formLabel" name="box-nine" placeholder="Edit me ...">
                                        </span>
                                        <span id="spacing">
                                            <input type="checkbox" id="b-ten" name="behaviour-check[]">
                                            <input id="box-ten" type="text" class="formLabel" name="box-ten" placeholder="Edit me ...">
                                        </span>
                                    </div>
                                </div>
                                @if (session('behaviour-status'))
                                    <div class="alert alert-danger">
                                        <strong>{{ session('behaviour-status') }}</strong>
                                    </div>
                                @endif
                            </div>
                            <div class="col">
                                <p class="title" id="interpretation-info">Interpretation</p>
                                <h6 style="text-align:center;">Enter all the interpretations to included in the quiz</h6>
                                <p style="text-align:center;">Fields left "Edit me..." or blank will not be included in the quiz</p>
                                <h6 style="text-align:center;">Select the correct answers</h6>
                                <div class="form-group row justify-content-center">
                                    <div>
                                        <span id="spacing">
                                            <input type="radio" id="i-one" name="interpretation-radio" value="1">
                                            <input id="inter-one" type="text" class="formLabel" name="inter-one" placeholder="Edit me ...">
                                        </span>
                                        <span id="spacing">
                                            <input type="radio" id="i-two" name="interpretation-radio" value="2">
                                            <input id="inter-two" type="text" class="formLabel" name="inter-two" placeholder="Edit me ...">
                                        </span>
                                        <span id="spacing">
                                            <input type="radio" id="i-three" name="interpretation-radio" value="3">
                                            <input id="inter-three" type="text" class="formLabel" name="inter-three" placeholder="Edit me ...">
                                        </span>
                                        <span id="spacing">
                                            <input type="radio" id="i-four" name="interpretation-radio" value="4">
                                            <input id="inter-four" type="text" class="formLabel" name="inter-four" placeholder="Edit me ...">
                                        </span>
                                        <span id="spacing">
                                            <!-- TODO ZAK changed these ID -->
                                            <input type="radio" id="i-fve" name="interpretation-radio" value="5">
                                            <input id="inter-fve" type="text" class="formLabel" name="inter-five" placeholder="Edit me ...">
                                        </span>
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
                                Create Quiz
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
@endsection
