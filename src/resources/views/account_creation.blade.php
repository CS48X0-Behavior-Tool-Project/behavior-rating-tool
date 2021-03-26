@extends('layouts.creation')

@section('content')

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
                        <!-- Grad Year -->
                        <div class="form-group row justify-content-center">
                            <div class="col-md-7">
                                <select class="form-control" id="gradYearSelect" name="year">
                                    <option selected>Select your anticipated grad year</option>
                                    <option id="na" value="">N/A (for admin, expert and TA use)</option>
                                    <option id="current" value="{{now()->year}}">{{now()->year}}</option>
                                    <option id="1" value="{{now()->year +1}}">{{now()->year +1}}</option>
                                    <option id="2" value="{{now()->year +2}}">{{now()->year +2}}</option>
                                    <option id="3" value="{{now()->year +3}}">{{now()->year +3}}</option>
                                    <option id="4" value="{{now()->year +4}}">{{now()->year +4}}</option>
                                    <option id="5" value="{{now()->year +5}}">{{now()->year +5}}</option>
                                </select>
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

                        <div class="form-group row justify-content-center">
                            <button type="submit" class="btn btn-primary" onclick="validate(event)">
                                {{ __('Create Account') }}
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('end-body-scripts')
    {{-- All ajax related scripts should be moved to the end-body-scripts section --}}
    <script src="{{ URL::asset('javascript/account_creation.js') }}"></script>
@endsection
