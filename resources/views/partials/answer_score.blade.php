<?php
    use App\Models\Answer;

    $answer = Answer::find($answer_id);
?>

@if ($answer)
    @if(Auth::user()->id !== $answer->user_id)
        @if (Auth::user()->answerUpVotes()->where('answer_id', $answer->id)->exists())
        <button type="button" class="increase-answer-score-btn" data-id="{{ $answer->id }}" style="width: 3rem; height: 3rem; border-radius: 50%; text-align: center; line-height: 3rem; font-size: 1.5rem; padding: 0; margin: 0; background-color: green;">+</button>
        @else
        <button type="button" class="increase-answer-score-btn" data-id="{{ $answer->id }}" style="width: 3rem; height: 3rem; border-radius: 50%; text-align: center; line-height: 3rem; font-size: 1.5rem; padding: 0; margin: 0;">+</button>
        @endif
        <h3 class="answer_score" data-id="{{ $answer->id }}" style="width: 3rem; margin: 0; text-align: center;">{{ $answer->score }}</h3>
        @if (Auth::user()->answerDownVotes()->where('answer_id', $answer->id)->exists())
        <button type="button" class="decrease-answer-score-btn" data-id="{{ $answer->id }}" style="width: 3rem; height: 3rem; border-radius: 50%; text-align: center; line-height: 3rem; font-size: 1.5rem; padding: 0; margin: 0; background-color: red;">-</button>
        @else
        <button type="button" class="decrease-answer-score-btn" data-id="{{ $answer->id }}" style="width: 3rem; height: 3rem; border-radius: 50%; text-align: center; line-height: 3rem; font-size: 1.5rem; padding: 0; margin: 0;">-</button>
        @endif
    @else
        <h3 class="answer_score" data-id="{{ $answer->id }}" style="width: 3rem; margin: 0; text-align: center;">{{ $answer->score }}</h3>
    @endif
@endif