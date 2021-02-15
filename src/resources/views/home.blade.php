@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Home') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    Hi {{ Auth::user()->first_name }} {{ Auth::user()->last_name }},
                    <br>You are currently logged in as a {{ Auth::user()->roles[0]->name }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
