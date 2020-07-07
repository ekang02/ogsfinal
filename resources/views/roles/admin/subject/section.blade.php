@extends('layouts.app')

@section('content')
<div class="container">
    <adminsubjectsection :user="{{ Auth::user() }}"></adminsubjectsection>
</div>
@endsection
