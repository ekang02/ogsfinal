@extends('layouts.app')

@section('content')
<div class="container">
    <principalsection :user="{{ Auth::user() }}"></principalsection>
</div>
@endsection
