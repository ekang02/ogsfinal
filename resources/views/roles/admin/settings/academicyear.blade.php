@extends('layouts.app')

@section('content')
<div class="container">
    <adminacademicyear :user="{{ Auth::user() }}"></adminacademicyear>
</div>
@endsection
