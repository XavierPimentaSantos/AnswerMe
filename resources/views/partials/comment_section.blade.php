<?php
    use App\Models\QuestionComment;
?>

@foreach($comments as $comment)
<div class="question-comment-body" style="display: flex; flex-direction: column; background-color: #edf2f7; border-radius: 5px; padding: 0.4rem;" id="question_comment_card_{{ $comment->id }}">
    <div style="display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
        <h5 id="question_comment_body_{{ $comment->id }}">{{ $comment->body }}</h5>
        <div>
            <button type="button" class="question-comment-edit-btn" data-comment-id="{{ $comment->id }}" data-question-id="{{ $comment->question_id }}">edit comment</button>
            <button type="button" class="question-comment-delete-btn" data-comment-id="{{ $comment->id }}" data-question-id="{{ $comment->question_id }}">delete comment</button>
        </div>
    </div>
    <div style="display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
        <p style="margin: 0;">{{ $comment->user->name }}</p>
        <p style="margin: 0;">{{ $comment->created_at }}</p>
    </div>
</div>

<div id="question_comment_edit_form_{{ $comment->id }}" class="hidden">
    @csrf
    <h5>Edit</h5>
    <input type="text" name="question_comment_body" id="question_comment_body_edit_input_{{ $comment->id }}" value="{{ $comment->body }}" required>
    <button type="button" id="question_comment_edit_btn_{{ $comment->id }}">Edit</button>
    <button type="button" id="question_comment_cancel_btn_{{ $comment->id }}">Cancel</button>
</div>
@endforeach