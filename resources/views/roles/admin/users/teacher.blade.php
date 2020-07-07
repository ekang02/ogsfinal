@extends('layouts.app')

@section('content')
<div class="container">
    <adminteacher :user="{{ Auth::user() }}"></adminteacher>
</div>
@endsection
