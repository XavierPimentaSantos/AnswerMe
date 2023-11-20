@extends('layouts.app')
@section('content')

@if(isset($user))
    <h2>User Details</h2>
    <p>Username: {{ $user->username }}</p>
    <!-- Display other user details as needed -->
    <a href="{{ route('admin.users.edit', $user->id) }}">Edit</a>
    <form action="{{ route('admin.users.delete', $user->id) }}" method="post">
        @csrf
        @method('delete')
        <button type="submit">Delete</button>
    </form>
@endif

@endsection