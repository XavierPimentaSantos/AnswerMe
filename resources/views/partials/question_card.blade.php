<?php
    use App\Models\Question;
?>

<div class="questions bg-gray-200 mb-3 p-4">
    <div class="question-card-body">
        <a href="{{ route('questions.show', $question->id) }}">
            <div class="question-title">
                <h2>{{ $question->title }}</h2>
                @if ($question->user_id)
                <p class="card-content text-red-700">Asked by: {{ $question->user->name }}</p>
                @endif
            </div>
        </a>
        <p class="card-content" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $question->content }}</p>
    </div>
    <div style="display: flex; flex-direction: row; gap: 4px;">
        @foreach ($question->tags as $tag)
        <div style="border: 2px solid black; border-radius: 3px; width: min-content; padding: 0 4px; ">{{ $tag->name }}</div>
        @endforeach
    </div>
    <a href="{{ route('questions.show', $question->id) }}" class="button bg-blue-500 text-white px-4 py-2 rounded mt-2 inline-block">See Answers</a>
</div>