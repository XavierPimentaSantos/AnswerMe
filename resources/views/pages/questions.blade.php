<!-- resources/views/questions/create.blade.php -->

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

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    
@endsection
