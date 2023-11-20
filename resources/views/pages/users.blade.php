
@extends('layouts.app') 

@section('content')
    <div class="container">
        <h2>All Users</h2>

        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td><a href="{{ route('', $user->id) }}">View Profile</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
