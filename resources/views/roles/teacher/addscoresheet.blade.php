@extends('layouts.app')

@section('content')
<div class="container">
    <addscoresheet :user="{{ Auth::user() }}"></addscoresheet>
</div>
@endsection
