@extends('layouts.users')

@if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
@endif

@section('content')
<div class="container">
    <div class="row col-12">
        <!-- Filter -->
        <div class="card col-4">
            <div class="card-header"> Filter Users </div>

            <div class="card-body">
                <form class="" action="index.html" method="post">
                    <input id="search" class="form-control" type="text" name="search" placeholder="Search Users..." onkeyup"">
                </form>
            </div>
        </div>

        <!-- Users -->
        <div class="card col-8">
            <div class="card-header"> Users </div>

            @if ($message = Session::get('deletion-message'))
                <div class="alert alert-success">
                    <strong>{{ $message }}</strong>
                </div>
            @endif

            @if ($message = Session::get('reset-message'))
                <div class="alert alert-success">
                    <strong>{{ $message }}</strong>
                </div>
            @endif

            <div class="card-body">
              <!-- TODO add filter options here, search bar and letter to select first letter of first and last name -->
                @foreach ($users as $user)
                    <p style="padding:5px"> {{$user->first_name}} {{$user->last_name}}
                    <a href="{{ url('/user/' . $user->id) }}" class="btn btn-secondary" style="margin-left: 10px">View User</a>
                    </p>
                @endforeach
            </div>
        </div>

    </div>
</div>
@endsection
