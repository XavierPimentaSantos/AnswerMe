<?php
    use App\Models\Answer;
    use App\Models\AnswerComment;

    $comments = $answer->comments()->get();
?>

@foreach ($comments as $comment)
<div class="answer-comment-body" style="display: flex; flex-direction: column; background-color: #edf2f7; border-radius: 5px; padding: 0.4rem;">
    <div style="display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
        <h5>{{ $comment->body }}</h5>
        <div>
            <button type="button" class="question-comment-edit-btn">edit comment</button>
            <button type="button" class="question-comment-delete-btn">delete comment</button>
        </div>
    </div>
    <div style="display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
        <p style="margin: 0;">{{ $comment->user->name }}</p>
        <p style="margin: 0;">{{ $comment->created_at }}</p>
    </div>
</div>
@endforeach