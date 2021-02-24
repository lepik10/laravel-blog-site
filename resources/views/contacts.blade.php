@extends('layout')

@section('content')
    <h1>Contacts</h1>

    @can('home.secret')
        <p><a href="{{ route('secret') }}">Special contact detail</a></p>
    @endcan
@endsection
