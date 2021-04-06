<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Animal Behaviour') }}</title>

    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous">
    </script>

    <!-- Styles -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <!-- Page Specific Scripts -->
    @yield('view_specific_scripts')

    <!-- Page Specific Styles -->
    @yield('view_specific_styles')
</head>

<link rel="stylesheet" href="{{ URL::asset('css/navbar.css') }}">

<body>
    <div id="app">
        <nav class="navbar navbar-expand navbar-dark shadow-sm" style="background-color: #fc8403;">
            <div class="container">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-dark">
                        <h4><a href="{{ url('quizzes') }}">{{ config('app.name') }}</a></h4>
                    </ul>
                    <!-- Right Side Of Navbar -->
                        @guest
                            <!-- Guests see nothing here -->
                        @else
                            <ul class="nav nav-tabs">
                              <li class="nav-item">
                                  <!-- Admin and TA -->
                                  @if (Bouncer::is(Auth::user())->an("admin", "ta"))
                                      <a class="nav-link" href="{{ url('users') }}">
                                          {{ __('Users') }}
                                      </a>
                                  @endif
                              </li>
                              <li class="nav-item">
                                  <a class="nav-link" href="{{ url('quizzes') }}">
                                      {{ __('Quizzes') }}
                                  </a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link" href="{{ url('review') }}">
                                    {{ __('Review') }}
                                  </a>
                              </li>
                              <li class="nav-item">
                                  <a class="nav-link active" href="{{ url('account') }}">
                                      <strong>{{ __('Account') }}</strong>
                                  </a>
                              </li>
                              <li class="nav-item">
                                  <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                      {{ __('Logout') }}
                                  </a>
                                  <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                      @csrf
                                  </form>
                              </li>
                            </ul>
                        @endguest

                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    @yield('end-body-scripts')
</body>

</html>
