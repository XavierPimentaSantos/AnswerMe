<?php
    use App\Models\Question;
?>

<div class="questions bg-gray-200 mb-3 p-4">
    <div class="question-card-body">
        <h2>{{ $question->score }}</h2>
        <a href="{{ route('questions.show', $question->id) }}">
            <div class="question-title">
                <div style="display: flex; flex-direction: row; gap: 2rem;">
                    <h2>{{ $question->title }}</h2>
                    @if (Auth::check() && Auth::user()->followedQuestions->contains($question->id))
                        <h4 class="material-symbols-outlined" style="color: green;">notifications</h4>
                    @endif
                </div>
                @if ($question->user_id)
                <p class="card-content text-red-700">Asked by: {{ $question->user->name }}</p>
                @endif
            </div>
        </a>
        <p class="card-content" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $question->content }}</p>
        <div id="images-container" class="m-2 flex overflow-x-auto">
            @foreach($question->images as $image)
                <img src="{{ asset($image->picture_path) }}" alt="Question Image"  style = "max-width: 10%; padding: 5px">
            @endforeach
        </div>
    </div>
    <div style="display: flex; flex-direction: row; gap: 4px;">
        @foreach ($question->tags as $tag)
        <div style="border: 2px solid black; border-radius: 3px; width: min-content; padding: 0 4px; ">{{ $tag->name }}</div>
        @endforeach
    </div>
    <a href="{{ route('questions.show', $question->id) }}" class="button bg-blue-500 text-white px-4 py-2 rounded mt-2 inline-block">See Answers</a>
</div>