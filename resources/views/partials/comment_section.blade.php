<?php
    use App\Models\QuestionComment;
?>

@foreach($questioncomments as $questioncomment)
<div class="question-comment-body" style="display: flex; flex-direction: column; background-color: #edf2f7; border-radius: 5px; padding: 0.4rem;" id="question_comment_card_{{ $questioncomment->id }}">
    <div style="display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
        <h5 id="question_comment_body_{{ $questioncomment->id }}">{{ $questioncomment->body }}</h5>
        <div>
            <button type="button" class="question-comment-edit-btn" data-comment-id="{{ $questioncomment->id }}" data-question-id="{{ $questioncomment->question_id }}">edit comment</button>
            <button type="button" class="question-comment-delete-btn" data-id="$questioncomment->id">delete comment</button>
        </div>
    </div>
    <div style="display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
        <p style="margin: 0;">{{ $questioncomment->user->name }}</p>
        <p style="margin: 0;">{{ $questioncomment->created_at }}</p>
    </div>
</div>

<div id="question_comment_edit_form_{{ $questioncomment->id }}" class="hidden">
    @csrf
    <h5>Edit</h5>
    <input type="text" name="question_comment_body" id="question_comment_body_edit_input_{{ $questioncomment->id }}" value="{{ $questioncomment->body }}" required>
    <button type="button" id="question_comment_edit_btn_{{ $questioncomment->id }}">Edit</button>
</div>
@endforeach