@extends('layouts.app')

@section('content')
<div class="container">
    <principalstaff :user="{{ Auth::user() }}"></principalstaff>
</div>
@endsection
