@extends('layouts.app')

@section('content')
<div class="container">
    <scoresheet :user="{{ Auth::user() }}"></scoresheet>
</div>
@endsection
