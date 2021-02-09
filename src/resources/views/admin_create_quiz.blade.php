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
                    <form>
                    <div class="row">
                        <div class="col">
                            <iframe src="https://www.youtube.com/embed/ofGy1zuj2rM" width="col-md-4" height="200"></iframe>
                            <br>
                            <p style="text-align:center; color: black; background-color: #f7f7f7;border-radius: 5px;border: 1px solid #dfdfdf;">Video information</p>

                            <div class="form-group row">
                                <label for="video-link" class="col-md-4 col-form-label text-md-right">Link</label>
                                <div class="col-md-8">
                                    <input id="video-link" type="text" class="form-control" name="video-link" placeholder="YouTube Link ..." required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="video-id" class="col-md-4 col-form-label text-md-right">ID</label>
                                <div class="col-md-8">
                                    <input id="video-id" type="text" class="form-control" name="video-id" placeholder="ID ..." required>
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
                            <p style="text-align:center; color: black; background-color: #f7f7f7;border-radius: 5px;border: 1px solid #dfdfdf;">Behaviours</p>

                            <h6 style="text-align:center;">Enter all that you wish to have included in the quiz</h6>
                            <input id="behaviours" type="text" class="form-control" name="behaviours" placeholder="Behaviours ..." required>
                            <br>
                            <h6 style="text-align:center;">Select the correct answers</h6>
                            <div class="form-group row justify-content-center">
                                <div>
                                    <span>
                                        <input type="checkbox" id="one" name="one" value="one">
                                        <label for="one"> These should</label><br>
                                    </span>
                                    <span>
                                        <input type="checkbox" id="two" name="two" value="two">
                                        <label for="two"> Autopopulate</label><br>
                                    </span>
                                    <span>
                                        <input type="checkbox" id="three" name="three" value="three">
                                        <label for="three"> Based on what</label><br>
                                    </span>
                                    <span>
                                        <input type="checkbox" id="four" name="four" value="four">
                                        <label for="four"> Is entered</label><br>
                                    </span>
                                    <span>
                                        <input type="checkbox" id="five_plus" name="five_plus" value="five_plus">
                                        <label for="five_plus"> Above</label><br>
                                    </span>
                                </div>
                            </div>

                        </div>
                        <div class="col">
                            <p style="text-align:center; color: black; background-color: #f7f7f7;border-radius: 5px;border: 1px solid #dfdfdf;">Interpretation</p>
                            <h6 style="text-align:center;">Enter all that you wish to have included in the quiz</h6>
                            <input id="interpretation" type="text" class="form-control" name="interpretation" placeholder="Interpretation ..." required>
                            <br>
                            <h6 style="text-align:center;">Select the correct answers</h6>
                            <div class="form-group row justify-content-center">
                                <div>
                                    <span>
                                        <input type="checkbox" id="one" name="one" value="one">
                                        <label for="one"> These should</label><br>
                                    </span>
                                    <span>
                                        <input type="checkbox" id="two" name="two" value="two">
                                        <label for="two"> Autopopulate</label><br>
                                    </span>
                                    <span>
                                        <input type="checkbox" id="three" name="three" value="three">
                                        <label for="three"> Based on what</label><br>
                                    </span>
                                    <span>
                                        <input type="checkbox" id="four" name="four" value="four">
                                        <label for="four"> Is entered</label><br>
                                    </span>
                                    <span>
                                        <input type="checkbox" id="five_plus" name="five_plus" value="five_plus">
                                        <label for="five_plus"> Above</label><br>
                                    </span>
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
