@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('Review all your scores if you're a student!') }}
                    {{ __('Review all your students scores if you're an admin!') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
