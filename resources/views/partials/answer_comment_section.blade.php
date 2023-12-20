<?php
    use App\Models\Answer;
    use App\Models\AnswerComment;
?>

@foreach ($answercomments as $comment)
<div id="answer_comment_card_{{ $comment->id }}" class="answer-comment-body" style="display: flex; flex-direction: column; background-color: #edf2f7; border-radius: 5px; padding: 0.4rem;">
    @include ('partials.answer_comment', ['comment' => $comment])
</div>
@endforeach

<div class="text-center mt-2 ">
    <div class="pagination-links">
        {{ $answercomments->links() }}
    </div>
</div>