@extends('layouts.app')

@section('content')
<div class="container">
    <reportprogress :user="{{ Auth::user() }}"></reportprogress>
</div>
@endsection
