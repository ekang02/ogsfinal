@extends('layouts.app')

@section('content')
<div class="container">
    <principalassessmentlevel :user="{{ Auth::user() }}"></principalassessmentlevel>
</div>
@endsection
