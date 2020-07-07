@extends('layouts.app')

@section('content')
<div class="container">
    <reportprogressstudent :user="{{ Auth::user() }}"></reportprogressstudent>
</div>
@endsection
