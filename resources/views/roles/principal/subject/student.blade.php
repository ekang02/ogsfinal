@extends('layouts.app')

@section('content')
<div class="container">
    <principalsubjectstudent :user="{{ Auth::user() }}"></principalsubjectstudent>
</div>
@endsection
