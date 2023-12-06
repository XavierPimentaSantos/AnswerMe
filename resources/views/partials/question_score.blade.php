<?php
    use App\Models\Question;

    $question = Question::find($question_id);
?>

@if ($question)
<div style="display: flex; flex-direction: column;">
    @if (Auth::user()->questionUpVotes()->where('question_id', $question->id)->exists())
    <button type="button" class="increase-question-score-btn" data-id="{{ $question->id }}" style="background-color: green;">increase</button>
    @else
    <button type="button" class="increase-question-score-btn" data-id="{{ $question->id }}">increase</button>
    @endif
    <h3 class="question_score" data-id="{{ $question->id }}">{{ $question->score }}</h3>
    @if (Auth::user()->questionDownVotes()->where('question_id', $question->id)->exists())
    <button type="button" class="decrease-question-score-btn" data-id="{{ $question->id }}" style="background-color: red;">decrease</button>
    @else
    <button type="button" class="decrease-question-score-btn" data-id="{{ $question->id }}">decrease</button>
    @endif
</div>
@endif