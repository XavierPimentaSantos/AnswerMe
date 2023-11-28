<!-- resources/views/questions/create.blade.php -->

<?php
    $available_tags = DB::table('tags')->get();
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
    
<!-- <script>
    let checkboxes = document.querySelectorAll('.tag-checkbox');

    checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            console.log('checkbox ticked');
            updateTags(); // when any checkbox is ticked/unticked, we want to update the tags that are shown
        });
    });

    function updateTags() {
        let checkedTags = document.querySelectorAll('.tag-checkbox:checked');
        let selectedTags = Array.from(checkedTags).map(checkbox => checkbox.value);

        // Make an AJAX request using the fetch API
        fetch('/update_tags', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                // You may need to include additional headers if required
            },
            body: JSON.stringify({ selectedTags: selectedTags }),
        })
        .then(response => response.text())
        .then(data => {
            // Update a portion of the page with the returned HTML
            document.getElementById('tag-section').innerHTML = data;
        })
        .catch(error => console.error('Error updating tags:', error));
    }
</script> -->

@endsection
