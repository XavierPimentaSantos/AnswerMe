<?php
    use App\Models\QuestionComment;
?>

@foreach($questioncomments as $comment)
<div id="question_comment_card_{{ $comment->id }}" class="question-comment-body" style="display: flex; flex-direction: column; background-color: #edf2f7; border-radius: 5px; padding: 0.4rem;">
    @include ('partials.question_comment', ['comment' => $comment])    
</div>
@endforeach

<div class="text-center mt-2 ">
    <div class="pagination-links">
        {{ $questioncomments->links() }}
    </div>
</div>