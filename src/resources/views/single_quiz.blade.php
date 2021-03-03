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
                <div class="card-header">{{ __('Attempting Quiz') }}</div>

                <div class="card-body">
                    <!-- Video Display -->
                    <div class="row justify-content-center">
                        <!-- TODO insert video display here -->
                        The video ID can be found using {{$video}}

                    </div>

                    <!-- TODO insert form data (action, method) -->
                    <form>
                        <!-- Questions -->
                        <div class="row justify-content-center">
                            <div class="col-md-6">
                                <p class="title">Select all behaviours you see displayed in the video</p>
                                @foreach ($options as $opt)
                                    @if ( $opt->type == "behaviour")
                                        <span id="spacing">
                                            <input type="checkbox" id="behaviour-check-{{$opt->title}}" name="behaviour-check"></button>
                                            <label for="behaviour-check-{{$opt->title}}">{{$opt->title}}</label>
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                            <div class="col-md-6">
                                <p class="title">Select your interpretation</p>
                                @foreach ($options as $opt)
                                    @if ( $opt->type == "interpretation")
                                    <span id="spacing">
                                        <input type="radio" id="interpretation-check-{{$opt->title}}" name="interpretation-check"></button>
                                        <label for="interpretation-check-{{$opt->title}}">{{$opt->title}}</label>
                                    </span>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <!-- Submit button -->

                    </form>



                    {{ __('Attempt a quiz!') }}
                    <table border = "1">
                    <tr>
                    <td>Id</td>
                    <td>Type</td>
                    <td>Title</td>
                    <td>Marking Scheme</td>
                    <td>Is Solution</td>
                    </tr>
                    <tr>
                    <td>{{ $opt->id }}</td>
                    <td>{{ $opt->type }}</td>
                    <td>{{ $opt->title }}</td>
                    <td>{{ $opt->marking_scheme }}</td>
                    <td>{{ $opt->is_solution }}</td>
                    </tr>
                    </table>

                    <?php echo 'video ID: ' . $video; ?>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
