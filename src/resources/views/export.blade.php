@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
<div class="card">
<div class="card-header">{{ __('Download Center') }}</div>
<div class="card-body" >
    <a href="{{ route('export_users_route') }}">Export Users</a>
    </div>
</div>
</div>

@endsection