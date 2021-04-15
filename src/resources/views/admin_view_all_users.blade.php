@extends('layouts.users')

@if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
@endif

@section('content')

@if (Bouncer::is(Auth::user())->an("admin", "ta"))
    <div class="row col-12 justify-content-center">
        <button class="btn btn-primary" type="button" name="button" onclick="window.location.href='/add_user'">Add Users</button>
    </div>
    <br>
@endif

<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css" rel="stylesheet">

<!-- Bootstrap core JavaScript-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<!-- Page level plugin JavaScript-->
<script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>

<!-- Icon styles -->
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

<div class="container">
    <div class="card col-12">
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
            <div class="container">
                <div style="display: flex; justify-content: flex-end">
                    <div class="dropdown">
                        <button type="button" class="btn btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background-color: #fc8403; color: white">
                            <i class="fas fa-download"></i> Download
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="{{ route('export_users_route') }}">CSV</a>
                            <!-- TODO -->
                            <a class="dropdown-item" href="{{ route('export_users_json') }}">JSON</a>
                        </div>
                    </div>
                </div>
                <br>
                <!-- Computer Screens -->
                <div class="d-none d-sm-none d-md-block">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th style="white-space: nowrap; width: 5%">View User Progress</th>
                                <th style="white-space: nowrap; width: 5%">Edit User</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>View User Progress</th>
                                <th>Edit User</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{$user->first_name}} {{$user->last_name}}</td>
                                    <td>{{$user->email}}</td>
                                    <td> <a href="{{ url('review/'.$user->id.'/all') }}" class="btn btn-info" style="margin-left: 10px">View User's Quizzes</a></td>
                                    <td>
                                        <div class="dropdown">
                                          <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Edit User
                                          </button>
                                          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                              <a class="dropdown-item" href="{{ url('users/reset/'.$user->id.'/') }}">Reset {{$user->first_name}}'s Password</a>
                                              {{-- ($user->email != env('ADMIN_USER_EMAIL') ensures you can't delete the Admin user --}}
                                              @if (Bouncer::is(Auth::user())->an("admin") && ($user->email != env('ADMIN_USER_EMAIL')))
                                                <a class="dropdown-item" href="{{ url('users/delete/'.$user->id.'/') }}" onclick="return confirm('Are you sure you want to delete {{$user->first_name}} {{$user->last_name}}?  This action is irreversible.')">Delete {{$user->first_name}} {{$user->last_name}}</a>
                                              @endif
                                          </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Screens -->
                <div class="d-block d-sm-block d-md-none d-lg-none d-xl-none">
                    <table class="table table-bordered" id="dataTableSmall" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th style="white-space: nowrap; width: 5%">Options</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Options</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{$user->first_name}} {{$user->last_name}}</td>
                                    <td>{{$user->email}}</td>
                                    <td>
                                        <div class="dropdown">
                                          <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                              User Options
                                          </button>
                                          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                              <a class="dropdown-item" href="{{ url('users/quizzes/'.$user->id.'/') }}">View {{$user->first_name}}'s Quizzes</a>
                                              <a class="dropdown-item" href="{{ url('users/reset/'.$user->id.'/') }}">Reset {{$user->first_name}}'s Password</a>
                                              <a class="dropdown-item" href="{{ url('users/delete/'.$user->id.'/') }}" onclick="return confirm('Are you sure you want to delete {{$user->first_name}} {{$user->last_name}}?  All associated records will be removed.  This action is irreversible.')">Delete {{$user->first_name}} {{$user->last_name}}</a>
                                          </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('end-body-scripts')
    {{-- All ajax related scripts should be moved to the end-body-scripts section --}}
    <script src="{{ asset('/javascript/view_users.js') }}"></script>
@endsection
