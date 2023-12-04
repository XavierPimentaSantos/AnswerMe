<!-- resources/views/questions/create.blade.php -->

<?php
    $available_tags = DB::table('tags')->pluck('name')->toArray();
    $sel_tags = array();
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

            <div id="tag-section" style="display: flex; flex-direction: row; flex-wrap: wrap; gap: 4px;">
                @csrf
                @include('partials.selected_tags', ['tags' => []]) 
            </div>

            <div class="form-group">
                <div>
                    @foreach ($available_tags as $available_tag)
                        <div style="display: none;">
                            <input type="checkbox" name="sel_tags[]" id="tag_{{ $available_tag }}" value="{{ $available_tag }}" class="tag-checkbox">
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="form-group" id="question_tag_container">
                <input type="text" name="tag_input" id="tag_input" list="tag_listing">
                <datalist id="tag_listing">
                    @foreach ($available_tags as $available_tag)
                        <option value="{{ $available_tag }}">{{ $available_tag }}</option>
                    @endforeach
                </datalist>
                <button id="add_tag" type="button">Add tag</button>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

@endsection
