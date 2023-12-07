<?php
    use App\Models\Question;

    $question = Question::find($question_id);
?>

@if ($question)
    @if (Auth::user()->questionUpVotes()->where('question_id', $question->id)->exists())
    <button type="button" class="increase-question-score-btn" data-id="{{ $question->id }}" style="width: fit-content; height: fit-content; padding: 3px 4px; background-color: green;">+</button>
    @else
    <button type="button" class="increase-question-score-btn" data-id="{{ $question->id }}" style="width: fit-content; height: fit-content; padding: 3px 4px;">+</button>
    @endif
    <h3 class="question_score" data-id="{{ $question->id }}">{{ $question->score }}</h3>
    @if (Auth::user()->questionDownVotes()->where('question_id', $question->id)->exists())
    <button type="button" class="decrease-question-score-btn" data-id="{{ $question->id }}" style="width: fit-content; height: fit-content; padding: 3px 4px; background-color: red;">-</button>
    @else
    <button type="button" class="decrease-question-score-btn" data-id="{{ $question->id }}" style="width: fit-content; height: fit-content; padding: 3px 4px;">-</button>
    @endif
@endif