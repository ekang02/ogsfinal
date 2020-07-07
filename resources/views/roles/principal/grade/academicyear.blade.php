@extends('layouts.app')

@section('content')
<div class="container">
    <principalacademicyear :user="{{ Auth::user() }}"></principalacademicyear>
</div>
@endsection
