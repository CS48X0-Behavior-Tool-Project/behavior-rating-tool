@extends('layouts.app')

@if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
@endif

@section('content')
<div class="container">
    <div class="row col-12">
        <div class="card col-4">
            <div class="card-header">Filter</div>
            <div class="card-body">
                <input id="search" class="form-control" type="text" name="search" placeholder="Search Quizzes..." onkeyup"">

                <!-- Number of attempts filter -->
                <div class="form-group row justify-content-center">
                    <br><p style="text-align:center;">Filter quizzes by your number of attempts</p>
                    <div>
                        <span>
                            <input type="checkbox" id="one" name="one" value="one">
                            <label for="one"> One</label><br>
                        </span>
                        <span>
                            <input type="checkbox" id="two" name="two" value="two">
                            <label for="two"> Two</label><br>
                        </span>
                        <span>
                            <input type="checkbox" id="three" name="three" value="three">
                            <label for="three"> Three</label><br>
                        </span>
                        <span>
                            <input type="checkbox" id="four" name="four" value="four">
                            <label for="four"> Four</label><br>
                        </span>
                        <span>
                            <input type="checkbox" id="five_plus" name="five_plus" value="five_plus">
                            <label for="five_plus"> Five +</label><br>
                        </span>
                    </div>
                </div>
                <!-- Animal filter -->
                <div class="form-group row justify-content-center">
                    <br><p style="text-align:center;">Filter quizzes by animal</p>
                    <div>

                        <!-- Populate dynamic radio button list for each animal species in database -->
                        <br>
                        @foreach($animals as $data)
                        <span>
                            <input type="radio" id="a-{{$data}}" name="animal-radio[]" value = "{{$data}}">
                            <label id="a-{{$data}}" type="text" name="a-{{$data}}"> {{$data}} </label>
                            <br>
                        </span>
                        @endforeach
                        
                    </div>
                </div>
            </div>
        </div>
        <div class="card col-8">
            <div class="card-header">Selection</div>
            <div class="card-body">

            </div>
        </div>
    </div>
</div>

@endsection
