@extends('layouts.app')

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
                          <label for="fname" class="col-md-4 col-form-label text-md-right">{{ __('First Name') }}</label>
                          <div class="col-md-6">
                              <input id="fname" type="text" class="form-control @error('name') is-invalid @enderror" name="fname" placeholder="John" required autofocus>
                              @error('name')
                                  <span class="invalid-feedback" role="alert">
                                      <strong>{{ $message }}</strong>
                                  </span>
                              @enderror
                          </div>
                      </div>

                      <div class="form-group row">
                          <label for="lname" class="col-md-4 col-form-label text-md-right">{{ __('Last Name') }}</label>
                          <div class="col-md-6">
                              <input id="lname" type="text" class="form-control @error('name') is-invalid @enderror" name="lname" placeholder="Smith" required autofocus>
                              @error('name')
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
                              <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="jsmith@upei.ca" required autofocus>
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
                                {{ __('Download Template File') }}
                            </button>
                        </form>
                    </div>
                    <br>
                    <div align="center">
                        <p style="text-align:center; color: black; background-color: #f7f7f7;border-radius: 5px;border: 1px solid #dfdfdf;">Add Multiple Users from a File</p>
                        <form action="/add_user" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name = "mycsv" id="users_upload"/>
                            <input type="submit" class="btn btn-secondary" value="Upload">
                        </form>
                    </div>
                    <br>

                    @if ($message = Session::get('user_count_message'))
                     <div class="alert alert-success">
                       <strong>{{ $message }}</strong>
                     </div>
                    @endif
                    <div align="center">
                        <img src="images/user_example2.png" alt="example csv" max_width="col-md-6">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
