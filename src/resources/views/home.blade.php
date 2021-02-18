@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Home') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('Welcome!') }}
                </div>

                <form action="{{route('users.destroy', ['user' => Auth::user()->id])}}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="submit">delete</button>
                </form>

                @if($message = Session::get('success'))
                    <div class="alert alert-success">
                       <strong>{{ $message }}</strong>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
