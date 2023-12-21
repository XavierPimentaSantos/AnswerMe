<?php
    use App\Models\Question;

    $question = Question::find($question_id);
?>

@if ($question)
    @if (Auth::check() && Auth::user()->id !== $question->user_id)
        @if (Auth::user()->questionUpVotes()->where('question_id', $question->id)->exists())
        <button type="button" class="increase-question-score-btn" data-id="{{ $question->id }}" style="width: 3rem; height: 3rem; border-radius: 50%; text-align: center; line-height: 3rem; font-size: 1.5rem; padding: 0; margin: 0; background-color: green;">+</button>
        @else
        <button type="button" class="increase-question-score-btn" data-id="{{ $question->id }}" style="width: 3rem; height: 3rem; border-radius: 50%; text-align: center; line-height: 3rem; font-size: 1.5rem; padding: 0; margin: 0;">+</button>
        @endif
        <h3 class="question_score" data-id="{{ $question->id }}" style="width: 3rem; margin: 0; text-align: center;">{{ $question->score }}</h3>
        @if (Auth::user()->questionDownVotes()->where('question_id', $question->id)->exists())
        <button type="button" class="decrease-question-score-btn" data-id="{{ $question->id }}" style="width: 3rem; height: 3rem; border-radius: 50%; text-align: center; line-height: 3rem; font-size: 1.5rem; padding: 0; margin: 0; background-color: red;">-</button>
        @else
        <button type="button" class="decrease-question-score-btn" data-id="{{ $question->id }}" style="width: 3rem; height: 3rem; border-radius: 50%; text-align: center; line-height: 3rem; font-size: 1.5rem; padding: 0; margin: 0;">-</button>
        @endif
    @else
        <h3 class="question_score" data-id="{{ $question->id }}" style="width: 3rem; margin: 0; text-align: center;">{{ $question->score }}</h3>
    @endif
@endif