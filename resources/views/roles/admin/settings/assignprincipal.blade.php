@extends('layouts.app')

@section('content')
<div class="container">
    <adminassignprincipal :user="{{ Auth::user() }}"></adminassignprincipal>
</div>
@endsection
