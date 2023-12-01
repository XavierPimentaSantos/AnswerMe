@extends('layouts.app')

@section('content')

<form method="POST" action="{{ route('admin.submit') }}">
    @csrf
    <label for="username">Search for a User:</label>
    <input type="text" id="username" name="username">
    <button type="submit">Search</button>
</form>

<a href="{{ route('register') }}" class="button button-outline">Create an Account</a>

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

@endsection