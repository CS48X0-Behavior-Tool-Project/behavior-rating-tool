@extends('layouts.app')

@if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
@endif

<link rel="stylesheet" href="{{ URL::asset('css/single_quiz.css') }}">

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Reviewing Quiz:  {{$code ?? 'QuizID'}}  Attempt {{$attempt ?? '#'}}</h5>
                    <p>Time Taken:  {{$time ?? '0:00'}}</p>
                </div>
                <div class="card-body">
                    <div style="text-align: right">
                        Behaviours: {{$behaviour_score ?? '0'}}/{{$max_behaviour_score ?? '0'}}  Interpretation: {{$interpretation_score ?? '0'}}/1
                    </div>
                    <br>

                    <!-- Video -->
                    <div class="row justify-content-center">
                        <video id="thumbnail" class="" width="80%" style="max-width: 100%; resize: both;" controls>
                            <source src="{{ route('videos.show', ['video' => $video]) }}" type="video/mp4"/>
                        </video>
                    </div>
                    <br>
                    <h6 style="text-align: center">Below are the responses you selected. Correct responses are highlighted.</h6>
                    <div class="row">
                        <!-- Behaviours -->
                        <div class="col">
                            <p class="title">Behaviours</p>
                            @if ($behaviour_score == $max_behaviour_score)
                                <h6 style="text-align: center">You correctly selected all behaviours.</h6>
                            @elseif ($behaviour_score > 0)
                                <h6 style="text-align: center">You correctly selected some behaviours.</h6>
                            @else
                                <h6 style="text-align: center">You did not correctly select any behaviours.</h6>
                            @endif

                            <div class="row justify-content-center">
                                <div>
                                  @foreach ($options as $opt)
                                      @if ( $opt->type == "behaviour")

                                          @if(in_array($opt->title, $behavior_answers,true))
                                              @if ( $opt->is_solution == 1)
                                                  <span style="background-color:#86EA89">
                                              @endif
                                              <input type="checkbox" id="behaviour-check-{{$opt->title}}" name="behaviour-check[]" disabled value="{{$opt->title}}" checked></button>
                                              <label for="behaviour-check-{{$opt->title}}">{{$opt->title}}</label>
                                          @else
                                              <input type="checkbox" id="behaviour-check-{{$opt->title}}" name="behaviour-check[]" disabled value="{{$opt->title}}"></button>
                                              <label for="behaviour-check-{{$opt->title}}">{{$opt->title}}</label>
                                          @endif

                                          </span>
                                          <br>
                                      @endif
                                  @endforeach
                                </div>
                            </div>
                        </div>
                        <!-- Interpretation -->
                        <div class="col">
                            <p class=title>Interpretation</p>
                            @if ($interpretation_score == 1)
                            <h6 style="text-align: center">Your interpretation was correct</h6>
                            @else
                            <h6 style="text-align: center">Your interpretation was incorrect</h6>
                            @endif
                            <div class="row justify-content-center">
                                <div class="">
                                    @foreach ($options as $opt)
                                        @if ( $opt->type == "interpretation")
                                            @if($opt->title == $interpretation_answers)
                                                @if ( $opt->is_solution == 1)
                                                    <span style="background-color:#86EA89">
                                                @endif
                                                <input type="radio" id="interpretation-check-{{$opt->title}}" name="interpretation-check" disabled  value="{{$opt->title}}" checked></button>
                                                <label for="interpretation-check-{{$opt->title}}">{{$opt->title}}</label>
                                            @else
                                                <input type="radio" id="interpretation-check-{{$opt->title}}" name="interpretation-check" disabled  value="{{$opt->title}}"></button>
                                                <label for="interpretation-check-{{$opt->title}}">{{$opt->title}}</label>
                                            @endif
                                            </span>
                                            <br>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
