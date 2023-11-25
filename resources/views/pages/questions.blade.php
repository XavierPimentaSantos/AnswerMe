<!-- resources/views/questions/create.blade.php -->

use Illuminate\Support\Facades\DB;

<?php
    $available_tags = DB::table('tags')->get();
    // $selected_tags = array();
?>

@extends('layouts.app') <!-- Adjust this based on your layout file -->

@section('content')
    <div class="container">
        <h2>Ask a Question</h2>

        <form action="{{ route('questions.store') }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" name="title" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="content">Content:</label>
                <textarea name="content" class="form-control" rows="5" required></textarea>
            </div>

            <div class="form-group" id="question_tag_container">
                @foreach ($selected_tags as $selected_tag)
                    <div class="tag_item">
                        {{ $selected_tag->name }}
                    </div>
                @endforeach
                <input id="tag_input" type="text" name="tags" list="tag_list" placeholder="Choose tag(s)">
                <datalist id="tag_list">
                    @foreach ($available_tags as $available_tag)
                        @if (!in_array($available_tag->id, $selected_tags))
                            <option value="{{ $available_tag->id }}">{{ $available_tag->name }}</option>
                        @endif
                    @endforeach
                </datalist>
                <button id="tag_adder">Add tag</button>
                
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    
@endsection
