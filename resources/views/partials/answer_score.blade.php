<?php
    use App\Models\Answer;

    $answer = Answer::find($answer_id);
?>

@if ($answer)
    @if (Auth::user()->answerUpVotes()->where('answer_id', $answer->id)->exists())
    <button type="button" class="increase-answer-score-btn" data-id="{{ $answer->id }}" style="width: fit-content; height: fit-content; padding: 3px 4px; background-color: green;">+</button>
    @else
    <button type="button" class="increase-answer-score-btn" data-id="{{ $answer->id }}" style="width: fit-content; height: fit-content; padding: 3px 4px;">+</button>
    @endif
    <h3 class="answer_score" data-id="{{ $answer->id }}">{{ $answer->score }}</h3>
    @if (Auth::user()->answerDownVotes()->where('answer_id', $answer->id)->exists())
    <button type="button" class="decrease-answer-score-btn" data-id="{{ $answer->id }}" style="width: fit-content; height: fit-content; padding: 3px 4px; background-color: red;">-</button>
    @else
    <button type="button" class="decrease-answer-score-btn" data-id="{{ $answer->id }}" style="width: fit-content; height: fit-content; padding: 3px 4px;">-</button>
    @endif
@endif