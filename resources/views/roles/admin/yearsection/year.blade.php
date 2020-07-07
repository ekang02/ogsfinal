@extends('layouts.app')

@section('content')
<div class="container">
    <adminyear :user="{{ Auth::user() }}"></adminyear>
</div>
@endsection
