@extends('layouts.app')

@section('content')
<div class="container">
    <principalclassadvisory :user="{{ Auth::user() }}"></principalclassadvisory>
</div>
@endsection
