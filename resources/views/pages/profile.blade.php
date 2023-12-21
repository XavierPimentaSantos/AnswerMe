@extends('layouts.app')

<?php
    use App\Models\Setting;

    $top_question = $user->questions()->orderByDesc('score')->first();
    $top_answer = $user->answers()->orderByDesc('score')->first();

    $preferences = Setting::find($user->id);
?>

@section('content')
<div class="text-center justify-center" id="profile_main">
    <div id="profile-view">
        <img src="{{ asset('profile_pictures/' . $user->profile_picture) }}" alt="Profile Picture" class="rounded-full border-4 border-black m-4 block mx-auto" style="width: 20rem; height: 20rem;">
        @if ($preferences->hide_name==0)
        <h2 id="profile-view-name">{{ $user->name }}</h2>
        @endif
        <h3 id="profile-view-username">{{ $user->username }}</h3>
        @if ($preferences->hide_email==0)
        <p id="profile-view-email">{{ $user->email }}</p>
        @endif
        <p id="profile-view-bio" style="margin-top: 1rem; text-align: left;">{{ $user->bio }}</p>
        @if ($preferences->hide_nation==0)
        <p id="profile-view-nation">{{ $user->nationality }}</p>
        @endif
        @if($preferences->hide_birth_date==0)
        <p id="profile-view-birthdate">Born on: {{ $user->birthdate }}</p>
        @endif
    
        @if (Auth::user()->isAdmin() && !$user->isBlocked() && !$user->isAdmin()) <!-- Check if the authenticated user is an admin -->
            <form method="POST" action="{{ route('admin.blockUser', ['username' => $user->name]) }}">
                @csrf
                <button type="submit">Block User</button>
            </form>
            @if (!$user->isModerator())
            <form method="POST" action="{{ route('admin.promoteUser', ['username' => $user->name]) }}">
                @csrf
                <button type="submit">Promote to Moderator</button>
            </form>
            @else
            <form method="POST" action="{{ route('admin.demoteUser', ['username' => $user->name]) }}">
                @csrf
                <button type="submit">Demote from Moderator</button>
            </form>
            @endif
        @endif

        <div id="profile-view-btns">
            @if(Auth::user()->isAdmin() || Auth::user()->id===$user->id)
            <button type="button" class="material-symbols-outlined bg-gray-200" id="edit-profile-btn" style="border: 2px solid black; border-radius: 2px; color: black;">edit</button>
            @endif
            @if(Auth::user()->isAdmin())
            <form method="POST" @if (!$user->isBlocked()) action="{{ route('admin.blockUser', ['username' => $user->name]) }}" @else action="{{ route('admin.unblockUser', ['username' => $user->name]) }}" @endif>
                @csrf
                <button type="submit" class="material-symbols-outlined bg-gray-200" style="border: 2px solid black; border-radius: 2px; @if (!$user->isBlocked()) color: red; @else color: green; @endif">block</button>
            </form>
            @endif
        </div>
    </div>

    <div id="profile-edit" style="display: none;">
        <div id="profile-edit-btns">
            <form method="DELETE" action="{{ route('profile.delete', ['username' => $user->name]) }}" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">
                @csrf
                <button type="submit" class="material-symbols-outlined bg-gray-200" style="border: 2px solid black; border-radius: 2px; color: red;">delete</button>
            </form>
            <button class="material-symbols-outlined bg-gray-200" type="button" id="update-profile-btn" style="border: 2px solid black; border-radius: 2px; color: green;" data-user="{{ $user->username }}">check</button>
            <button class="material-symbols-outlined bg-gray-200" type="button" id="cancel-update-profile-btn" style="border: 2px solid black; border-radius: 2px; color: yellow;">close</button>
        </div>

        <img src="{{ asset('profile_pictures/' . $user->profile_picture) }}" alt="Profile Picture" class="rounded-full border-4 border-black m-4 block mx-auto" style="width: 20rem; height: 20rem;">

        <div id="pfp_input_">
            <label for="profile_picture"></label>
            <input type="file" id="profile_picture_input" name="profile_picture" accept="image/*">
        </div>

        <div id="name_input_">
            <label for="name">Name:</label>
            <input type="text" id="name_input" name="name" value="{{ $user->name }}" maxlength="250" required>
            <input type="checkbox" name="name_opt" id="name_opt" class="toggle-checkbox">
            <label for="name_opt" class="material-symbols-outlined">visibility</label>
        </div>

        <div id="username_input_">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username_input" value="{{ $user->username }}" maxlength="250" required>
        </div>

        <div id="email_input_">
            <label for="email">Email:</label>
            <input type="email" id="email_input" name="email" value="{{ $user->email }}" maxlength="250" required>
            <input type="checkbox" name="email_opt" id="email_opt">
            <label for="email_opt" class="material-symbols-outlined">visibility</label>
        </div>

        <textarea name="bio_input" id="bio_input" cols="20" rows="6" maxlength="300" style="margin-top: 1rem;">{{ $user->bio }}</textarea>

        <div id="birthdate_opt_">
            <h4 id="bd_h4">{{ $user->birthdate }}</h4>
            <input type="checkbox" name="birthdate_opt" id="birthdate_opt" class="toggle-checkbox">
            <label for="birthdate_opt" class="material-symbols-outlined">visibility</label>
        </div>

        <div id="nationality_opt_">
            <h4 id="nat_h4">{{ $user->nationality }}</h4>
            <input type="checkbox" name="nationality_opt" id="nationality_opt" class="toggle-checkbox">
            <label for="nationality_opt" class="material-symbols-outlined">visibility</label>
        </div>

        <div id="success-message" class="hidden alert alert-success"></div>
        <div id="error-message" class="hidden alert alert-danger"></div>
    </div>

    <div id="top-posts">
        @if ($top_question)
        <div>
            <h3>Top Question</h3>
            @include('partials.question_card', ['question' => $top_question])
        </div>
        @endif

        @if ($top_answer)
        <div>
            <h3>Top Answer</h3>
            <div class="answers bg-gray-100 mb-3 p-4">
                <div style="display: flex; flex-direcion: row; gap: 5px;">
                    <div style="display: flex; flex-direction: column; width: 3rem; justify-content: space-around;">
                        @csrf
                        @include ('partials.answer_score', ['answer_id' => $top_answer->id])
                    </div>

                    <div style="display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between; width: -webkit-fill-available;">
                        <div style="display: flex; flex-direction: row;">
                            <h4 class="font-bold">{{ $top_answer->title }}</h4>
                            @if ($top_answer->correct === true)
                            <h4 class="material-symbols-outlined" style="color: green;">check</h4>
                            @else
                            <h4 class="material-symbols-outlined" style="display: none; color: green;">check</h4>
                            @endif
                        </div>
                    </div>
                </div>
                <p>{{ $top_answer->content }}</p>
            </div>
        </div>
        @endif
    </div>

</div>

@endsection