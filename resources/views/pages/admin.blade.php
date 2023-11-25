@extends('layouts.app')

@section('content')

<form method="POST" action="{{ route('admin.submit') }}">
    @csrf
    <label for="username">Username:</label>
    <input type="text" id="username" name="username">
    <button type="submit">Submit</button>
</form>

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

@endsection