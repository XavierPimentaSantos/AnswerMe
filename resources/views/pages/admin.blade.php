@extends('layouts.app')

@section('content')

<?php
    $users = DB::table("users")->get();
    $tags = DB::table("tags")->get();
?>

<div class="tabs">
@if (Auth::user()->isAdmin())
        <button class="tablink active" onclick="openTab('TagManagement', this)">Tag Management</button>
        <button class="tablink" onclick="openTab('UserManagement', this)">User Management</button>
@endif
</div>

@if (Auth::user()->isModerator())
<div id="TagManagement" class="tabcontent">
    <h2>All Tags:</h2>
    @foreach ($tags as $tag)
        <div class="tag">
            {{ $tag->name }}
            <form method="POST" action="{{ route('tag.delete', $tag->id) }}" onsubmit="return confirm('Are you sure you want to delete this tag?')">
                @csrf
                @method('DELETE')
                <button class="delete-button" type="submit">X</button>
            </form>
        </div>
    @endforeach
    
    <form method="POST" action="{{ route('admin.addTag') }}">
    @csrf
    <label for="tag">Add a New Tag:</label>
    <input type="text" id="tag" name="tag">
    <button type="submit">Add Tag</button>
    </form>

</div>
@endif

@if (Auth::user()->isAdmin())
<div id="UserManagement" class="tabcontent">
<form method="POST" action="{{ route('admin.submit') }}">
    @csrf
    <label for="username">Search for a User:</label>
    <input type="text" id="username" name="username" list="username_list">
    <datalist id="username_list">
        @foreach ($users as $user)
            <option value="{{ $user->username }}">{{ $user->name }}</option>
        @endforeach
    </datalist>
    <button type="submit">Search</button>
</form>
<a href="{{ route('admin.create') }}" class="button">Create an Account</a>
</div>
@endif

<script>
    function openTab(tabId, elmnt) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablink");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].style.backgroundColor = "";
        }
        document.getElementById(tabId).style.display = "block";
        elmnt.style.backgroundColor = "#ccc";
    }

    // Get the element with id="defaultOpen" and click on it
    document.getElementsByClassName("tablink")[0].click();
</script>




@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


@endsection