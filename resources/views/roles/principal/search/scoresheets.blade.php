@extends('layouts.app')

@section('content')
<div class="container">
    <principalscoresheets :user="{{ Auth::user() }}"></principalscoresheets>
</div>
@endsection
