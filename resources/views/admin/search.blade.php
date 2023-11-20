@extends('layouts.app')
@section('content')

<form action="{{ route('admin.users.index') }}" method="post">
    @csrf
    @method('put')
    <label for="username">Username:</label>
    <input type="text" name="username" id="username" required>
    <button type="submit">Search</button>
</form>

@endsection