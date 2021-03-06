@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('View All Users') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('View all users if you are an admin!') }}

                    <!-- Zak for testing purposes display your data here -->

                    <table border = "1">
                    <tr>
                    <td>Id</td>
                    <td>Username (think we're getting rid of this eventually)</td>
                    <td>First Name</td>
                    <td>Last Name</td>
                    <td>Email</td>
                    <td>Options</td>
                    </tr>
                    @foreach ($users as $user)
                    <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->first_name }}</td>
                    <td>{{ $user->last_name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->options }}</td>
                    <td><a href="{{ url('/user/' . $user->id) }}" class="btn btn-xs btn-info pull-right">Select</a><td>
                    </tr>
                    @endforeach
                    </table>


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
