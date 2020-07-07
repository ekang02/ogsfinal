@extends('layouts.app')

@section('content')
<div class="container">
    <principalstudents :user="{{ Auth::user() }}"></principalstudents>
</div>
@endsection
