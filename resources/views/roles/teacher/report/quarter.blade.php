@extends('layouts.app')

@section('content')
<div class="container">
    <reportterm :user="{{ Auth::user() }}"></reportterm>
</div>
@endsection
