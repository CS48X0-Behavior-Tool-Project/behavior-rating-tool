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

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <!-- <link href="{{ asset('/css/styles.css') }}" rel="stylesheet"> -->

    <!-- Page Specific Scripts -->
    @yield('view_specific_scripts')

    <!-- Page Specific Styles -->
    @yield('view_specific_styles')
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand navbar-dark shadow-sm" style="background-color: #fc8403">
            <div class="container">
                @guest
                    <a class="navbar-brand" href="{{ route('login') }}">
                        {{ config('app.name') }}
                    </a>
                @else
                    <a class="navbar-brand" href="{{ url('/home') }}">
                        {{ config('app.name') }}
                    </a>
                @endguest
                <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                    </ul>
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        @guest
                            <!-- Guests see nothing here -->
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <!-- Admin and TA -->
                                    @if (request()->user()->can('create-users'))
                                        <a class="dropdown-item" href="{{ url('add_user') }}">
                                            {{ __('Add Users') }}
                                        </a>
                                    @endif
                                    @if (request()->user()->can('view-users-page'))
                                        <a class="dropdown-item" href="{{ url('users') }}">
                                            {{ __('View All Users') }}
                                        </a>
                                    @endif
                                    @if (request()->user()->can('create-quizzes'))
                                        <a class="dropdown-item" href="{{ url('create_quiz') }}">
                                            {{ __('Create New Quiz') }}
                                        </a>
                                    @endif
                                    <!-- Admin, TA, and Experts -->
                                    @if (request()->user()->can('update-quizzes'))
                                        <a class="dropdown-item" href="{{ url('edit_quiz') }}">
                                            {{ __('Edit Quiz') }}
                                        </a>
                                    @endif
                                    <!-- All users  -->
                                    @if (request()->user()->can('conduct-quizzes'))
                                        <a class="dropdown-item" href="{{ url('quizzes') }}">
                                            {{ __('Quizzes') }}
                                        </a>
                                    @endif
                                    @if (request()->user()->can('export-users'))
                                        <a class="dropdown-item" href="{{ url('export') }}">
                                            {{ __('Export') }}
                                        </a>
                                    @endif
                                    @if (request()->user()->can('view-profile'))
                                        <a class="dropdown-item" href="{{ url('account') }}">
                                            {{ __('Account') }}
                                        </a>
                                    @endif
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
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
