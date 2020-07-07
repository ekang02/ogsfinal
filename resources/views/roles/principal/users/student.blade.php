@extends('layouts.app')

@section('content')
<div class="container">
    <principalstudent :user="{{ Auth::user() }}"></principalstudent>
</div>
@endsection
