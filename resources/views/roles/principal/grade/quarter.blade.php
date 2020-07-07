@extends('layouts.app')

@section('content')
<div class="container">
    <principalgradequarter :user="{{ Auth::user() }}"></principalgradequarter>
</div>
@endsection
