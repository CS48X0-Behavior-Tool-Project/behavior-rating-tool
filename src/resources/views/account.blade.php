@extends('layouts.account')

@section('content')

<script type="text/javascript" src="{{ URL::asset('javascript/account.js') }}"></script>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <form id="account" action="/account" method="post">
              @csrf
              <div class="card">
                  <div class="card-header">
                      Change Your Name
                  </div>
                  <div class="card-body">
                      <!-- First Name -->
                      <div class="form-group row">
                          <label class="col-md-4 col-form-label text-md-right" for="fname">First Name</label>
                          <div class="col-md-6">
                              <input id="fname" type="text" class="form-control @error('name') is-invalid @enderror" name="fname" autocomplete="given-name" placeholder="{{auth()->user()->first_name}}">
                              @error('name')
                                  <span class="invalid-feedback" role="alert">
                                      <strong>{{ $message }}</strong>
                                  </span>
                              @enderror
                          </div>
                      </div>
                      <!-- Last Name -->
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right" for="lname">Last Name</label>
                            <div class="col-md-6">
                              <!-- TODO make placeholder first name -->
                                <input id="lname" type="text" class="form-control @error('name') is-invalid @enderror" name="lname" autocomplete="family-name" placeholder="{{auth()->user()->last_name}}">

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        @if ($message = Session::get('name_message'))
                         <div class="alert alert-success">
                           <strong>{{ $message }}</strong>
                         </div>
                        @endif
                  </div>
              </div>
              <br>
              <div class="card">
                  <div class="card-header">
                      Change Your Email
                  </div>
                  <div class="card-body">
                    <div class="form-group row">
                        <label for="old-email" class="col-md-4 col-form-label text-md-right">Old Email</label>

                        <div class="col-md-6">
                            <input id="old-email" type="email" class="form-control @error('email') is-invalid @enderror" name="old-email" placeholder="{{auth()->user()->email}}" pattern="{{auth()->user()->email}}" onchange="this.setCustomValidity(this.validity.patternMismatch ? 'The email you have entered does not match the email used for this account' : '');">

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="email" class="col-md-4 col-form-label text-md-right">New Email</label>

                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="New Email" pattern="^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]{2,3}" onchange="this.setCustomValidity(this.validity.patternMismatch ? 'The email you have entered is not valid' : '');">

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="email-confirm" class="col-md-4 col-form-label text-md-right">Confirm New Email</label>
                        <div class="col-md-6">
                            <input id="email-confirm" type="email" class="form-control" name="email-confirm" placeholder="Confirm New Email">
                        </div>
                    </div>

                    @if ($message = Session::get('email_error'))
                     <div class="alert alert-danger">
                       <strong>{{ $message }}</strong>
                     </div>
                    @endif

                    @if ($message = Session::get('email_message'))
                     <div class="alert alert-success">
                       <strong>{{ $message }}</strong>
                     </div>
                    @endif
                  </div>
              </div>
              <br>
              <div class="card">
                  <div class="card-header">
                      Change Your Password
                  </div>
                  <div class="card-body">
                    <div class="form-group row">
                        <label for="old-password" class="col-md-4 col-form-label text-md-right">Old Password</label>

                        <div class="col-md-6">
                            <input id="old-password" type="password" class="form-control @error('password') is-invalid @enderror" name="old-password" autocomplete="current-password" placeholder="Old Password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password" class="col-md-4 col-form-label text-md-right">New Password</label>

                        <div class="col-md-6">
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password" placeholder="New Password" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$" onchange="this.setCustomValidity(this.validity.patternMismatch ? 'Password must have at least 8 characters. 1 upper case, 1 lower case, 1 number, and 1 special character' : '');">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Confirm New Password</label>

                        <div class="col-md-6">
                            <input id="password-confirm" type="password" class="form-control" name="password-confirm" autocomplete="new-password" placeholder="Confirm New Password">
                        </div>
                    </div>

                    @if ($message = Session::get('password_error'))
                     <div class="alert alert-danger">
                       <strong>{{ $message }}</strong>
                     </div>
                    @endif

                    @if ($message = Session::get('password_message'))
                     <div class="alert alert-success">
                       <strong>{{ $message }}</strong>
                     </div>
                    @endif
                  </div>
              </div>
              <br>

              <!-- Save Button -->
              <div class="form-group row mb-0">
                  <div class="col-md-6 offset-md-4">
                      <button id="save" type="submit" name="save" class="btn btn-primary" onclick="validate(event)">
                          Save Changes
                      </button>
                  </div>
              </div>
            </form>
        </div>
    </div>
</div>

@endsection
