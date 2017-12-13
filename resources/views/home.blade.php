@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="jumbotron">
                <h1>Hello, {{ Auth::user()->nickname }}!</h1>
                <p>You have <strong>{{ Auth::user()->balance }}</strong> MK5.</p>
                <p>To get more MK5, you can <a href="{{ route('mining') }}">mine it</a> by yourself.</p>
            </div>
        </div>
    </div>
</div>
@endsection
