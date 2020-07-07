@extends('layouts.app')

@section('content')
<div class="container">
    <principalgrade :user="{{ Auth::user() }}"></principalgrade>
</div>
@endsection
