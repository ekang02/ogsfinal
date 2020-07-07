@extends('layouts.app')

@section('content')
<div class="container">
    <studentgrade :user="{{ Auth::user() }}"></studentgrade>
</div>
@endsection
