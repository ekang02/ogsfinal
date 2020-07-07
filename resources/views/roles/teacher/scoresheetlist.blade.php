@extends('layouts.app')

@section('content')
<div class="container">
    <scoresheetlist :user="{{ Auth::user() }}"></scoresheetlist>
</div>
@endsection
