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

            <!-- Delete My Account Button -->
            <div id="profile-view" style="display: block;">
                <!-- Display Profile Picture -->
                <p id="name"><strong>Name:</strong> {{ $user->name }}</p>
                <p id="email"><strong>Email:</strong> {{ $user->email }}</p>
            </div>
        <div id="profile-edit" style="display: none;">

            <form id="edit-profile-form" enctype="multipart/form-data" method="POST" action="{{ route('profile.edit')}}">                
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