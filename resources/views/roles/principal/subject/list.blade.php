@extends('layouts.app')

@section('content')
<div class="container">
    <principalsubject :user="{{ Auth::user() }}"></principalsubject>
</div>
@endsection
