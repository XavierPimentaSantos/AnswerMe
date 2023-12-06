<?php
    use App\Models\Question;

    $question = Question::find($question_id);
?>

@if ($question)
<div style="display: flex; flex-direction: column;">
    <button type="button" class="increase-question-score-btn" data-id="{{ $question->id }}">increase</button>
    <h3 class="question_score" data-id="{{ $question->id }}">{{ $question->score }}</h3>
    <button type="button" class="decrease-question-score-btn" data-id="{{ $question->id }}">decrease</button>
</div>
@endif