@extends('layouts.app')

@section('content')
<div class="container">
    <principalsubjectsection :user="{{ Auth::user() }}"></principalsubjectsection>
</div>
@endsection
