@extends('layouts.app')

@section('content')
<div class="container">
    <adminsection :user="{{ Auth::user() }}"></adminsection>
</div>
@endsection
