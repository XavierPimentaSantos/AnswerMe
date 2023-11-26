<!-- resources/views/questions/create.blade.php -->

<?php
    $available_tags = DB::table('tags')->get();
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

            <!-- <div class="form-group" id="question_tag_container">
                <fieldset>
                    @foreach ($available_tags as $available_tag)
                    <div style="display: flex; flex-direction: row;">
                        <input type="checkbox" name="sel_tags[]" id="tag_{{ $available_tag->id }}" value="{{ $available_tag->id }}" class="tag-checkbox">
                        <label for="tag_{{ $available_tag->id }}">{{ $available_tag->name }}</label>
                    </div>
                    @endforeach
                </fieldset>
            </div> -->

            <div class="form-group" id="question_tag_container">
                <div id="tag_list">
                    @foreach ($available_tags as $available_tag)
                        <div style="display: flex; flex-direction: row;">
                            <input type="checkbox" name="sel_tags[]" id="tag_{{ $available_tag->id }}" value="{{ $available_tag->id }}" class="tag-checkbox">
                            <label for="tag_{{ $available_tag->id }}">{{ $available_tag->name }}</label>
                        </div>
                    @endforeach
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    
@endsection
