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
                            <input type="radio" id="one" name="attempts-radio[]" value="one">
                            <label for="one"> One</label><br>
                        </span>
                        <span>
                            <input type="radio" id="two" name="attempts-radio[]" value="two">
                            <label for="two"> Two</label><br>
                        </span>
                        <span>
                            <input type="radio" id="three" name="attempts-radio[]" value="three">
                            <label for="three"> Three</label><br>
                        </span>
                        <span>
                            <input type="radio" id="four" name="attempts-radio[]" value="four">
                            <label for="four"> Four</label><br>
                        </span>
                        <span>
                            <input type="radio" id="five_plus" name="attempts-radio[]" value="five_plus">
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
              <table border = "1">
              <tr>
              <td>Id</td>
              <td>Code</td>
              <td>Animal</td>
              <td>Video</td>
              <td>Question</td>
              </tr>
              @foreach ($quizzes as $quiz)
              <tr>
              <td>{{ $quiz->id }}</td>
              <td>{{ $quiz->code }}</td>
              <td>{{ $quiz->animal }}</td>
              <td>{{ $quiz->video }}</td>
              <td>{{ $quiz->question }}</td>
              </tr>
              @endforeach
              </table>
            </div>
        </div>
    </div>
</div>

@endsection
