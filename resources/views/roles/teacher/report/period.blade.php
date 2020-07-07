@extends('layouts.app')

@section('content')
<div class="container">
    <reportperiod :user="{{ Auth::user() }}"></reportperiod>
</div>
@endsection
