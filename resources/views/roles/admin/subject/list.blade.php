@extends('layouts.app')

@section('content')
<div class="container">
    <adminsubject :user="{{ Auth::user() }}"></adminsubject>
</div>
@endsection
