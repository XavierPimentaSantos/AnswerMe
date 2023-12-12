@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center">
    <div class="text-center justify-center">
            <h2 id="profile-header">User Profile</h2>
            <h2 id="edit-profile-header" style="display: none;">Edit Profile</h2>
            <div id = "profile-picture">
                <img src="{{ asset('profile_pictures/' . $user->profile_picture) }}" alt="Profile Picture" class="rounded-full profile-size border-4 border-black m-4"> 
            </div>    
            <a class="button text-sm rounded px-1 py-1 my-1" id="edit-profile-btn">Edit User Profile</a>

        @if (Auth::user()->isAdmin() && !$user->isBlocked()) <!-- Check if the authenticated user is an admin -->
            <form method="POST" action="{{ route('admin.blockUser', ['username' => $user->name]) }}">
                @csrf
                <button type="submit">Block User</button>
            </form>
        @endif

        @if (Auth::user()->isAdmin() && $user->isBlocked()) <!-- Check if the authenticated user is an admin -->
            <form method="POST" action="{{ route('admin.unblockUser', ['username' => $user->name]) }}">
                @csrf
                <button type="submit">Unblock User</button>
            </form>
        @endif

        <form method="POST" action="{{ route('profile.delete', ['username' => $user->name]) }}" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">
            @csrf
            @method('DELETE')
            <button type="submit">Delete Account</button>
        </form>

        <div id="profile-view">
            <p id = "name"><strong>Name:</strong> {{ $user->name }}</p>
            <p id = "email"><strong>Email:</strong> {{ $user->email }}</p>
        </div>

        <div id="profile-edit" style="display: none;">

            <form id="edit-profile-form" enctype="multipart/form-data" method="POST" action="{{ route('profile.edit', ['username' => $user->name]) }}">                
                @csrf
                @method('POST')

                <label for="profile_picture"></label>
                <input type="file" id="profile_picture" name="profile_picture" accept="image/*">

                <label for="name">Name:</label>
                <input type="text" id = "name-input" name="name" value="{{ $user->name }}" required>

                <label for="email">Email:</label>
                <input type="email" id = "email-input" name="email" value="{{ $user->email }}" required>


                <button class = "button" type="submit" id="update-profile-btn">Update Profile</button>
            </form>

            <div id="success-message" class="hidden alert alert-success"></div>
            <div id="error-message" class="hidden alert alert-danger"></div>

        </div>
    </div>
</div>

@endsection