@extends('layouts.app')

@section('content')
<div class="container">
    <scoresheetlistprint :user="{{ Auth::user() }}"></scoresheetlistprint>
</div>
@endsection
