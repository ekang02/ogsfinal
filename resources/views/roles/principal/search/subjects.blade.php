@extends('layouts.app')

@section('content')
<div class="container">
    <principalsubjects :user="{{ Auth::user() }}"></principalsubjects>
</div>
@endsection
