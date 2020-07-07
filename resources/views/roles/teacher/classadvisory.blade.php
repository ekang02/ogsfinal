@extends('layouts.app')

@section('content')
<div class="container">
    <teacherclassadvisory :user="{{ Auth::user() }}"></teacherclassadvisory>
</div>
@endsection
