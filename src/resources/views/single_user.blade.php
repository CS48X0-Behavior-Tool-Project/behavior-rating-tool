@extends('layouts.app')

@section('content')

<table border = "1">
<tr>
  <td>Id</td>
  <td>Username</td>
  <td>First Name</td>
  <td>Last Name</td>
  <td>Email</td>
  <td>Options</td>
  </tr>
  <tr>
  <td>{{ $user->id }}</td>
  <td>{{ $user->name }}</td>
  <td>{{ $user->first_name }}</td>
  <td>{{ $user->last_name }}</td>
  <td>{{ $user->email }}</td>
  <td>{{ $user->options }}</td>
  </tr>
@endsection
