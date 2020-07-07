@extends('layouts.app')

@section('content')
<div class="container">
    <principalteacher :user="{{ Auth::user() }}"></principalteacher>
</div>
@endsection
