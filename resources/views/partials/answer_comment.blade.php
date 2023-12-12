<div style="display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
    <h5 id="answer_comment_body_{{ $comment->id }}">{{ $comment->body }}</h5>
    @if ($comment->user_id === Auth::user()->id)
    <div>
        <button type="button" class="answer-comment-edit-btn" data-comment-id="{{ $comment->id }}" data-answer-id="{{ $comment->answer_id }}">edit comment</button>
        <button type="button" class="answer-comment-delete-btn" data-comment-id="{{ $comment->id }}" data-answer-id="{{ $comment->answer_id }}">delete comment</button>
    </div>
    @endif
</div>
<div style="display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
    <p style="margin: 0;">{{ $comment->user->name }} at {{ $comment->updated_at }} @if ($comment->edited == 1) (edited) @endif</p>
</div>

<div id="answer_comment_edit_form_{{ $comment->id }}" class="hidden">
    @csrf
    <h5>Edit</h5>
    <input type="text" name="answer_comment_body" id="answer_comment_body_edit_input_{{ $comment->id }}" value="{{ $comment->body }}" required>
    <button type="button" id="answer_comment_edit_btn_{{ $comment->id }}">Edit</button>
    <button type="button" id="answer_comment_cancel_btn_{{ $comment->id }}">Cancel</button>
</div>