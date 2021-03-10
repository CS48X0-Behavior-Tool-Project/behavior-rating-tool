@extends('layouts.app')

@if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
@endif

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{$user->first_name}} {{$user->last_name}}</div>


                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="row justify-content-center">
                                <button type="button" name="button" class="btn btn-primary" style="padding:10px">Reset Users Password</button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row justify-content-center">
                                <button type="button" name="button" class="btn btn-primary" style="padding:10px">View Users Quizzes</button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row justify-content-center">
                                <button type="button" name="button" class="btn btn-secondary" style="padding:10px">Delete User</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
