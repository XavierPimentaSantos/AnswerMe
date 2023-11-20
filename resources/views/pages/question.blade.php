@extends('layouts.app')

@section('content')

<article class="card" data-id="{{ $question->id }}">
            <div class="questions bg-gray-200 mb-3 p-4">
                <div class="question-card-body">
                <a href="{{ route('questions.show', $question->id) }}">
                        <div class="question-title">
                            <h2>{{ $question->title }}</h2>
                            <!-- Add more details as needed -->
                        </div>
                </a>
                    <p class="card-content">{{ $question->content }}</p>
                </div>
                <a href="{{ route('questions.show', $question->id) }}" class="button bg-blue-500 text-white px-4 py-2 rounded mt-2 inline-block">See Answers</a>
            </div>
</article>

@if (Auth::check())
<form action="{{ route('answers.store', ['question_id' => $question->id]) }}" method="post">
    @csrf
    <label for="title">Title:</label>
    <input type="text" id="title" name="title" required>
    <label for="content">Content:</label>
    <textarea id="content" name="content" rows="4" required></textarea>
    <button type="submit">Create Answer</button>
</form>
@endif

@endsection