@extends('layouts.app')

@section('content')

<table border = "1">
<tr>
<td>Id</td>
<td>Type</td>
<td>Title</td>
<td>Marking Scheme</td>
<td>Is Solution</td>
</tr>
@foreach ($options as $opt)
<tr>
<td>{{ $opt->id }}</td>
<td>{{ $opt->type }}</td>
<td>{{ $opt->title }}</td>
<td>{{ $opt->marking_scheme }}</td>
<td>{{ $opt->is_solution }}</td>
</tr>
@endforeach
</table>

<?php echo 'video ID: ' . $video; ?>

@endsection
