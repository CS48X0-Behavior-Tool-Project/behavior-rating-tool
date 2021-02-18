@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Home') }}</div>

                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="card-body">
                    Hi {{ Auth::user()->first_name }} {{ Auth::user()->last_name }},
                    <br>You are currently logged in as a {{ Auth::user()->roles[0]->name }}
                </div>

                @if($message = Session::get('success'))
                    <div class="alert alert-success">
                       <strong>{{ $message }}</strong>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection
