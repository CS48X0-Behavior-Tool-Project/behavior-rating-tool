@extends('layouts.app')

@section('content')

<script type="text/javascript" src="{{ URL::asset('javascript/account_creation.js') }}"></script>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Activate Your Account</div>
                <div class="card-body">
                    <form method="POST" action="/confirmation">
                      @csrf
                        <!-- Password -->
                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$" onchange="this.setCustomValidity(this.validity.patternMismatch ? 'Password must have at least 8 characters. 1 upper case, 1 lower case, 1 number, and 1 special character' : '');" required>

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <!-- Confirm Password -->
                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control @error('password') is-invalid @enderror" name="password-confirm" required>

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <br>
                        <p style="text-align:center;">Please indicate how much previous experience you have with horses</p>
                        <!-- Sruvey -->
                        <div class="form-group row justify-content-center">
                            <div>
                                <span>
                                  <input type="radio" id="none" name="experience" value="none" required>
                                  <label for="none">None</label>
                                </span><br>

                                <span>
                                  <input type="radio" id="beginner" name="experience" value="beginner">
                                  <label for="beginner">Beginner</label>
                                </span><br>

                                <span>
                                  <input type="radio" id="intermediate" name="experience" value="intermediate">
                                  <label for="intermediate">Intermediate</label>
                                </span><br>

                                <span>
                                  <input type="radio" id="advanced" name="experience" value="advanced">
                                  <label for="advanced">Advanced</label>
                                </span><br>

                                <span>
                                  <input type="radio" id="expert" name="experience" value="expert">
                                  <label for="expert">Expert</label>
                                </span><br>

                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary" onclick="validate(event)">
                                    {{ __('Create Account') }}
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
