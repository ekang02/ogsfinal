@extends('layouts.app')

@section('content')
<div class="container">
    <principalcriteria :user="{{ Auth::user() }}"></principalcriteria>
</div>
@endsection
