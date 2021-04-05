@extends('layouts.users')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">{{ __('Add Single User') }}</div>
                    <div class="card-body">
                        <form method="POST" action="/add_user">
                            @csrf
                            <!-- Name -->
                            <div class="form-group row">
                                <label for="first_name"
                                    class="col-md-4 col-form-label text-md-right">{{ __('First Name') }}</label>
                                <div class="col-md-6">
                                    <input id="first_name" type="text"
                                        class="form-control @error('first_name') is-invalid @enderror" name="first_name"
                                        placeholder="John" required autofocus>
                                    @error('first_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="last_name"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Last Name') }}</label>
                                <div class="col-md-6">
                                    <input id="last_name" type="text"
                                        class="form-control @error('last_name') is-invalid @enderror" name="last_name"
                                        placeholder="Smith" required autofocus>
                                    @error('last_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Email') }}</label>
                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                        name="email" placeholder="johndoe@upei.ca" required autofocus>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <!-- Role (dropdown) -->
                            <div class="form-group row">
                                <label for="role" class="col-md-4 col-form-label text-md-right">{{ __('Role') }}</label>
                                <div class="col-md-6">
                                    <select class="" name="role">
                                        <option value="student">Student</option>
                                        <option value="admin">Admin</option>
                                        <option value="expert">Expert</option>
                                        <option value="ta">TA</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" name="add_single_user" class="btn btn-primary">
                                        {{ __('Add User') }}
                                    </button>
                                </div>
                            </div>
                            <br>

                            @if ($message = Session::get('add_message'))
                                <div class="alert alert-success">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>


            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">{{ __('Add Multiple Users') }}</div>
                    <div class="card-body" align="center">
                        <div align="center">
                            <form method="get" action="template_files/add_user_template.csv">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Download CSV Template') }}
                                </button>
                            </form>
                        </div>
                        <br>
                        <div align="center">
                            <form method="post" action="/add_user/json_download">
                              @csrf
                                <button type="submit" class="btn btn-primary" name="jsonButton">
                                    {{ __('Download JSON Template') }}
                                </button>
                            </form>
                        </div>
                        <br>
                        <div align="center">
                            <p
                                style="text-align:center; color: black; background-color: #f7f7f7;border-radius: 5px;border: 1px solid #dfdfdf;">
                                Add Multiple Users from a File</p>
                            <form action="/add_user" method="post" enctype="multipart/form-data">
                                @csrf
                                <input type="file" name="mycsv" id="users_upload" />
                                <input type="submit" class="btn btn-secondary" value="Upload">
                            </form>
                        </div>
                        <br>
                        @if ($message = Session::get('file_error_message'))
                            <div class="alert alert-danger">
                                <strong>{{ $message }}</strong>
                            </div>

                        @endif

                        @if ($message = Session::get('user_count_message'))
                            <div class="alert alert-info">
                                <strong>{{ $message }}</strong>
                            </div>

                        @endif
                        @if ($message = Session::get('duplicate_email_error') and $count = Session::get('duplicate_email_count'))
                                <ul class="list-group">
                                    <li class="list-group-item invalid-feedback">{{$count}} Duplicate Emails Found</li>
                                    @foreach ($message as $email)
                                        <li class="list-group-item list-group-item-danger text-left"> {{ $email }}
                                        </li>
                                    @endforeach
                                </ul>
                        @endif
                        @if ($message = Session::get('invalid_email_error') and $count = Session::get('invalid_email_count'))
                                <ul class="list-group">
                                    <li class="list-group-item invalid-feedback">{{$count}} Emails Are Invalid</li>
                                    @foreach ($message as $email)
                                        <li class="list-group-item list-group-item-danger text-left"> {{ $email }}
                                        </li>
                                    @endforeach
                                </ul>
                        @endif
                        @if ($message = Session::get('missing_emails_error') and $count = Session::get('missing_emails_count'))
                                <ul class="list-group">
                                    <li class="list-group-item invalid-feedback">{{$count}} Missing Emails</li>
                                    @foreach ($message as $missing)
                                        <li class="list-group-item list-group-item-danger text-left"> {{ $missing }}
                                        </li>
                                    @endforeach
                                </ul>
                        @endif
                        @if ($message = Session::get('missing_firstnames_error') and $count = Session::get('missing_firstnames_count'))
                                <ul class="list-group">
                                    <li class="list-group-item invalid-feedback">{{$count}} Missing First Names</li>
                                    @foreach ($message as $missing)
                                        <li class="list-group-item list-group-item-danger text-left"> {{ $missing }}
                                        </li>
                                    @endforeach
                                </ul>
                        @endif
                        @if ($message = Session::get('missing_lastnames_error') and $count = Session::get('missing_lastnames_count'))
                                <ul class="list-group">
                                    <li class="list-group-item invalid-feedback">{{$count}} Missing Last Names</li>
                                    @foreach ($message as $missing)
                                        <li class="list-group-item list-group-item-danger text-left"> {{ $missing }}
                                        </li>
                                    @endforeach
                                </ul>
                        @endif
                        <br>
                        <div align="center">
                            <img src="images/user_example2.png" alt="example csv" max_width="col-md-6">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
