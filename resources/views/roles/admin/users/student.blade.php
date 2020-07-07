@extends('layouts.app')

@section('content')
<div class="container">
    <adminstudent :user="{{ Auth::user() }}"></adminstudent>
</div>
@endsection
