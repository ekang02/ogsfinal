@extends('layouts.app')

@section('content')
<div class="container">
    <subject :user="{{ Auth::user() }}"></subject>
</div>
@endsection
