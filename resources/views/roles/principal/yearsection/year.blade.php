@extends('layouts.app')

@section('content')
<div class="container">
    <principalyear :user="{{ Auth::user() }}"></principalyear>
</div>
@endsection
