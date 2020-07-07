@extends('layouts.app')

@section('content')
<div class="container">
    <teacherdashboard :user="{{ Auth::user() }}"></teacherdashboard>
</div>
@endsection
