@extends('layouts.app')

@if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
@endif

<style>
    .formLabel {
        text-align:center;
        color: black;
        border-radius: 5px;
        border: 1px solid #dfdfdf;
        min-width: 75px;
        min-height: 30px;
        max-width: 200px;
    }
</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
<script type="text/javascript">
    // Updates video thumbnail when a file is selected
    $(document).ready(function(){
        $("#video-upload").on("input", function(){
            var link = $("#video-upload").val().split("\\")[2];
            console.log(link);
            // TODO both of these just downloads the video, not actually updating the iframe src
            // document.getElementById('thumbnail').src = "videos/" + link;
            $("#thumbnail").attr("src","videos/"+link);
        })
    });
    // TODO need something that clears the choose file field if they prefer youtube over their local selection

    // Updates video thumbnail when a youtube link is entered
    $(document).ready(function(){
        // https://www.youtube.com/watch?v=K2972jP4ntY&ab_channel=JennaMarbles
        // watch?v= ... &
        // hxZss9dLpgI
        $("#video-link").on("input", function(){
            if($('#video-link').val().length > 11){
                var link = $('#video-link').val().substring(32,43);
                console.log(link);
                $("#thumbnail").attr("src","https://www.youtube.com/embed/"+link+"?rel=0&amp;showinfo=0");

            } else {
                $("#thumbnail").attr("src","https://www.youtube.com/embed/"+$('#video-link').val()+"?rel=0&amp;showinfo=0");
            }
        })
    });
</script>

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Create A New Quiz') }}</div>
                <div class="card-body">
                    <form method="POST" action="/create_quiz">
                      @csrf
                    <div class="row">
                        <div class="col">
                            <iframe id="thumbnail" src="" width="col-md-4" height="200"></iframe>
                            <br>
                            <br>
                            <p style="text-align:center; color: black; background-color: #f7f7f7;border-radius: 5px;border: 1px solid #dfdfdf;">Import Video</p>
                            <div class="row justify-content-center">
                                <input type="file" id="video-upload" accept="video/*">
                            </div>
                            <!-- <input type="submit" class="btn btn-secondary" value="Upload"> -->
                            <br>
                            <div class="form-group row">
                                <label for="video-link" class="col-md-4 col-form-label text-md-right">Youtube Link</label>
                                <div class="col-md-8">
                                    <input id="video-link" type="text" class="form-control" name="video-link" placeholder="YouTube Link ...">
                                </div>
                            </div>

                            <p style="text-align:center; color: black; background-color: #f7f7f7;border-radius: 5px;border: 1px solid #dfdfdf;">Video information</p>
                            <div class="form-group row">
                                <label for="video-id" class="col-md-4 col-form-label text-md-right">ID</label>
                                <div class="col-md-8">
                                    <input id="video-id" type="text" class="form-control" name="video-id" placeholder="Autogenerated ID">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="video-name" class="col-md-4 col-form-label text-md-right">Name</label>
                                <div class="col-md-8">
                                    <input id="video-name" type="text" class="form-control" name="video-name" placeholder="If different from ID ...">
                                </div>
                            </div>

                        </div>

                        <div class="col">
                            <p style="text-align:center; color: black; background-color: #f7f7f7;border-radius: 5px;border: 1px solid #dfdfdf;">Behaviours</p>

                            <h6 style="text-align:center;">Enter all the behaviours to included in the quiz</h6>
                            <p style="text-align:center;">Fields left "Edit me..." or blank will not be included in the quiz</p>
                            <h6 style="text-align:center;">Check the correct answers</h6>
                            <div class="form-group row justify-content-center">
                                <div>
                                    <!--<span>
                                        <input type="checkbox" id="b-one" name="behaviour-checks">
                                        <label id="b-one" contentEditable="true" class="formLabel" onchange="check(this)">Edit me...</label><br>
                                    </span>-->
                                    <span>
                                        <input type="checkbox" id="b-one" name="b-one">
                                        <!--<label id="b-two" contentEditable="true" class="formLabel">Edit me...</label><br>-->
                                        <input id="box-one" type="text" class="formLabel" name="box-one" placeholder="Edit me ..."><br>
                                    </span>
                                    <span>
                                        <input type="checkbox" id="b-two" name="b-two">
                                        <!--<label id="b-two" contentEditable="true" class="formLabel">Edit me...</label><br>-->
                                        <input id="box-two" type="text" class="formLabel" name="box-two" placeholder="Edit me ..."><br>
                                    </span>
                                    <span>
                                        <input type="checkbox" id="b-three" name="b-three">
                                        <!--<label id="b-two" contentEditable="true" class="formLabel">Edit me...</label><br>-->
                                        <input id="box-three" type="text" class="formLabel" name="box-three" placeholder="Edit me ..."><br>
                                    </span>
                                    <span>
                                        <input type="checkbox" id="b-four" name="b-four">
                                        <!--<label id="b-two" contentEditable="true" class="formLabel">Edit me...</label><br>-->
                                        <input id="box-four" type="text" class="formLabel" name="box-four" placeholder="Edit me ..."><br>
                                    </span>
                                    <span>
                                        <input type="checkbox" id="b-five" name="b-five">
                                        <!--<label id="b-two" contentEditable="true" class="formLabel">Edit me...</label><br>-->
                                        <input id="box-five" type="text" class="formLabel" name="box-five" placeholder="Edit me ..."><br>
                                    </span>
                                    <span>
                                        <input type="checkbox" id="b-six" name="b-six">
                                        <!--<label id="b-two" contentEditable="true" class="formLabel">Edit me...</label><br>-->
                                        <input id="box-six" type="text" class="formLabel" name="box-six" placeholder="Edit me ..."><br>
                                    </span>
                                    <span>
                                        <input type="checkbox" id="b-seven" name="b-seven">
                                        <!--<label id="b-two" contentEditable="true" class="formLabel">Edit me...</label><br>-->
                                        <input id="box-seven" type="text" class="formLabel" name="box-seven" placeholder="Edit me ..."><br>
                                    </span>
                                    <span>
                                        <input type="checkbox" id="b-eight" name="b-eight">
                                        <!--<label id="b-two" contentEditable="true" class="formLabel">Edit me...</label><br>-->
                                        <input id="box-eight" type="text" class="formLabel" name="box-eight" placeholder="Edit me ..."><br>
                                    </span>
                                    <span>
                                        <input type="checkbox" id="b-nine" name="b-nine">
                                        <!--<label id="b-two" contentEditable="true" class="formLabel">Edit me...</label><br>-->
                                        <input id="box-nine" type="text" class="formLabel" name="box-nine" placeholder="Edit me ..."><br>
                                    </span>
                                    <span>
                                        <input type="checkbox" id="b-ten" name="b-ten">
                                        <!--<label id="b-two" contentEditable="true" class="formLabel">Edit me...</label><br>-->
                                        <input id="box-ten" type="text" class="formLabel" name="box-ten" placeholder="Edit me ..."><br>
                                    </span>

                                    <!--<span>
                                        <input type="checkbox" id="b-three" name="behaviour-checks">
                                        <label id="b-three" contentEditable="true" class="formLabel">Edit me...</label><br>
                                    </span>
                                    <span>
                                        <input type="checkbox" id="b-four" name="behaviour-checks">
                                        <label id="b-four" contentEditable="true" class="formLabel">Edit me...</label><br>
                                    </span>
                                    <span>
                                        <input type="checkbox" id="b-five" name="behaviour-checks">
                                        <label id="b-five" contentEditable="true" class="formLabel">Edit me...</label><br>
                                    </span>
                                    <span>
                                        <input type="checkbox" id="b-six" name="behaviour-checks">
                                        <label id="b-six" contentEditable="true" class="formLabel">Edit me...</label><br>
                                    </span>
                                    <span>
                                        <input type="checkbox" id="b-seven" name="behaviour-checks">
                                        <label id="b-seven" contentEditable="true" class="formLabel">Edit me...</label><br>
                                    </span>
                                    <span>
                                        <input type="checkbox" id="b-eight" name="behaviour-checks">
                                        <label id="b-eight" contentEditable="true" class="formLabel">Edit me...</label><br>
                                    </span>
                                    <span>
                                        <input type="checkbox" id="b-nine" name="behaviour-checks">
                                        <label id="b-nine" contentEditable="true" class="formLabel">Edit me...</label><br>
                                    </span>
                                    <span>
                                        <input type="checkbox" id="b-ten" name="behaviour-checks">
                                        <label id="b-ten" contentEditable="true" class="formLabel">Edit me...</label><br>
                                    </span>-->
                                </div>
                            </div>

                        </div>
                        <div class="col">
                            <p style="text-align:center; color: black; background-color: #f7f7f7;border-radius: 5px;border: 1px solid #dfdfdf;">Interpretation</p>
                            <h6 style="text-align:center;">Enter all the interpretations to included in the quiz</h6>
                            <p style="text-align:center;">Fields left "Edit me..." or blank will not be included in the quiz</p>
                            <h6 style="text-align:center;">Select the correct answers</h6>
                            <div class="form-group row justify-content-center">
                                <div>

                                      <input type="radio" id="i-one" name="interpretation-radio">
                                      <label id="i-one" contentEditable="true" class="formLabel" onchange="check(this)">Edit me...</label><br>

                                      <input type="radio" id="i-two" name="interpretation-radio">
                                      <label id="i-two" contentEditable="true" class="formLabel">Edit me...</label><br>

                                      <input type="radio" id="i-three" name="interpretation-radio">
                                      <label id="i-three" contentEditable="true" class="formLabel">Edit me...</label><br>

                                      <input type="radio" id="i-four" name="interpretation-radio">
                                      <label id="i-four" contentEditable="true" class="formLabel">Edit me...</label><br>

                                      <input type="radio" id="i-five" name="interpretation-radio">
                                      <label id="i-five" contentEditable="true" class="formLabel">Edit me...</label><br>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <button type="submit" class="btn btn-primary">
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
