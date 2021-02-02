@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">{{ __('Add Single User') }}</div>
                <div class="card-body">
                  <!-- TODO add action route -->
                  <form method="POST" action="">
                      @csrf
                      <!-- Name -->
                      <div class="form-group row">
                          <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('New User Name') }}</label>
                          <div class="col-md-6">
                              <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="John Smith" required autofocus>
                              @error('name')
                                  <span class="invalid-feedback" role="alert">
                                      <strong>{{ $message }}</strong>
                                  </span>
                              @enderror
                          </div>
                      </div>
                      <!-- Email -->
                      <div class="form-group row">
                          <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('New User Email') }}</label>
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
                              <button type="submit" class="btn btn-primary">
                                  {{ __('Add User') }}
                              </button>
                          </div>
                      </div>
                  </form>
                </div>
            </div>
        </div>


        <div class="col-md-6">
            <div class="card">
                <div class="card-header">{{ __('Add Multiple Users') }}</div>
                <div class="card-body">
                    <div align="center">
                        <button type="submit" class="btn btn-primary">
                            {{ __('From Form') }}
                        </button>

                        <button type="submit" class="btn btn-primary">
                            {{ __('From File') }}
                        </button>
                    </div>

                    <br>
                    <img src="images/user_example.png" alt="example csv" max_width="col-md-6">
                    <br>
                    {{__('Option to download template file')}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
