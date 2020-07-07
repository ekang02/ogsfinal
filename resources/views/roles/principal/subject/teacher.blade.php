@extends('layouts.app')

@section('content')
<div class="container">
    <principalsubjectteacher :user="{{ Auth::user() }}"></principalsubjectteacher>
</div>
@endsection
