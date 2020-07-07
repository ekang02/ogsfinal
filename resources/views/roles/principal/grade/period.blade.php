@extends('layouts.app')

@section('content')
<div class="container">
    <principalgradeperiod :user="{{ Auth::user() }}"></principalgradeperiod>
</div>
@endsection
