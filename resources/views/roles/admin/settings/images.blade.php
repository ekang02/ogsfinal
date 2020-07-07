@extends('layouts.app')

@section('content')
<div class="container">
    <adminimages :user="{{ Auth::user() }}"></adminimages>
</div>
@endsection
