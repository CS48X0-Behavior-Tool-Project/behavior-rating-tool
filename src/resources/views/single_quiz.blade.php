@extends('layouts.app')

@if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
@endif

<style>
    .title {
        text-align: center;
        color: black;
        background-color: #f7f7f7;
        border-radius: 5px;
        border: 1px solid #dfdfdf;
    }
</style>
<!-- Timer -->
<script>
    countDownDate = 0;
    if (countDownDate) {
        countDownDate = new Date(countDownDate);
    } else {
        countDownDate = new Date();
        localStorage.setItem('startDate', countDownDate);
    }
    // Update the count down every 1 second
    var x = setInterval(function() {
        // Get todays date and time
        var now = new Date().getTime();
        // Find the distance between now an the count down date
        var distance = now - countDownDate.getTime();
        // Time calculations for days, hours, minutes and seconds
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);
        // Output the result in an element with id="demo"
        document.getElementById("demo").innerHTML =  hours.toLocaleString('en-US', {minimumIntegerDigits: 2}) + ":" + minutes.toLocaleString('en-US', {minimumIntegerDigits: 2}) + ":" + seconds.toLocaleString('en-US', {minimumIntegerDigits: 2});
    }, 1000);
</script>

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <!-- TODO inserts -->
                <div class="card-header">Attempting Quiz:  {{$quiz->id ?? 'QuizID'}}  {{$attempt ?? 'Attempt#'}}</div>
                <div class="card-body">

                    <form>
                        <!-- Stopwatch -->
                        <div class="col-md-4">
                            TIMER
                            <p id="demo"></p>
                        </div>
                        <br>

                        <!-- Video -->
                        <div class="row justify-content-center">
                            <!-- TODO Video -->
                            <iframe id="thumbnail" src="" width="col-md-4" height="200"></iframe>
                        </div>
                        <br>

                        <!-- Questions -->
                        <div class="row">
                            <!-- Behaviours -->
                            <div class="col">
                                <p class="title">Behaviours</p>

                                <h6 style="text-align:center;">Select all the behaviours you see in the video</h6>
                                <p style="text-align:center;">+1 Right answers</p>
                                <p style="text-align:center;">-1 Wrong answers</p>

                                <div class="form-group row justify-content-center">
                                    <div>
                                        @foreach ($options as $opt)
                                            @if ( $opt->type == "behaviour")
                                                <span>
                                                    <input type="checkbox" id="behaviour-check-{{$opt->title}}" name="behaviour-check"></button>
                                                    <label for="behaviour-check-{{$opt->title}}">{{$opt->title}}</label>
                                                </span>
                                                <br>
                                            @endif
                                        @endforeach

                                    </div>
                                </div>
                            </div>
                            <!-- Interpretation -->
                            <div class="col">
                                <p class="title">Interpretation</p>
                                <h6 style="text-align:center;">Select your interpretation based on the behaviours displayed</h6>
                                <p style="text-align:center;">This is either right or wrong</p>
                                <div class="form-group row justify-content-center">
                                    <div>
                                        @foreach ($options as $opt)
                                            @if ( $opt->type == "interpretation")
                                                <span id="spacing">
                                                    <input type="radio" id="interpretation-check-{{$opt->title}}" name="interpretation-check"></button>
                                                    <label for="interpretation-check-{{$opt->title}}">{{$opt->title}}</label>
                                                </span>
                                                <br>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center">
                            <button type="submit" class="btn btn-primary">
                                Submit Responses
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
