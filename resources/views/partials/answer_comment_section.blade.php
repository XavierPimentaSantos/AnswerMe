<?php
    use App\Models\Answer;
    use App\Models\AnswerComment;

    $comments = $answer->comments()->get();
?>

@foreach ($comments as $comment)
<div id="answer_comment_card_{{ $comment->id }}" class="answer-comment-body" style="display: flex; flex-direction: column; background-color: #edf2f7; border-radius: 5px; padding: 0.4rem;">
    @include ('partials.answer_comment', ['comment' => $comment])
</div>
@endforeach