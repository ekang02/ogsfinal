@extends('layouts.app')

@section('content')
<div class="container">
    <adminprincipal :user="{{ Auth::user() }}"></adminprincipal>
</div>
@endsection
