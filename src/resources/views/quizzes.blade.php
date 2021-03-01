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
                        <span>
                            <input type="checkbox" id="1" name="1" value="1">
                            <label for="1"> This will need</label><br>
                        </span>
                        <span>
                            <input type="checkbox" id="2" name="2" value="2">
                            <label for="2"> to be auto-populated</label><br>
                        </span>
                        <span>
                            <input type="checkbox" id="3" name="3" value="3">
                            <label for="3"> from the database</label><br>
                        </span>
                        <span>
                            <input type="checkbox" id="4" name="4" value="4">
                            <label for="4"> based on what animals</label><br>
                        </span>
                        <span>
                            <input type="checkbox" id="5" name="5" value="5">
                            <label for="5"> are available</label><br>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card col-8">
            <div class="card-header">Selection</div>
            <div class="card-body">
                <!-- @foreach ($users as $user)
                  <tr>
                  <td>{{ $user->id }}</td>
                  <td>{{ $user->first_name }}</td>
                  <td>{{ $user->last_name }}</td>
                  <td>{{ $user->city_name }}</td>
                  <td>{{ $user->email }}</td>
                  </tr>
                @endforeach -->
            </div>
        </div>
    </div>
</div>

@endsection
