<?php
    use App\Models\Question;
?>

<div class="question_card bg-gray-200 mb-3 p-4 border-2 border-black rounded-sm">
    <h2 class="question_card_score">{{ $question->score }}</h2>
    <div class="question_card_title" style="display: flex; flex-direction: row; gap: 0.4rem;">
        <h2><a href="{{ route('questions.show', $question->id) }}">{{ $question->title }}</a></h2>
        @if (Auth::check() && Auth::user()->followedQuestions->contains($question->id))
        <h2 class="material-symbols-outlined" style="color: green;">notifications</h2>
        @endif
    </div>
    <p class="question_card_content" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $question->content }}</p>
    <a class="question_card_author" href="{{ route('profile.showUser', ['username' => $question->user->username]) }}">{{ $question->user->username }}</a>
    <div class="question_card_tags" style="display: flex; flex-direction: row; gap: 4px;">
        @foreach ($question->tags as $tag)
        <div style="border: 1px solid black; border-radius: 2px; width: min-content; padding: 0 0.1rem">{{ $tag->name }}</div>
        @endforeach
    </div>
</div>