@extends('layouts.app')

@section('content')

<div class="container">
    <principaldashboard :user="{{ Auth::user() }}"></principaldashboard>
</div>
@endsection
