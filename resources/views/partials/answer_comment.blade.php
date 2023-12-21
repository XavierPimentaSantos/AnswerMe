<div style="display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
    <h4 id="answer_comment_body_{{ $comment->id }}">{{ $comment->body }}</h4>
    @if ($comment->user_id === Auth::user()->id || Auth::user()->isModerator())
    <div>
        <div class="tooltip">
            <button type="button" class="material-symbols-outlined bg-gray-200 answer-comment-edit-btn" style="border: 2px solid black; border-radius: 2px; color: black;" data-comment-id="{{ $comment->id }}" data-answer-id="{{ $comment->answer_id }}">edit</button>
            <p class="tooltiptext">Edit comment</p>
        </div>
        <div class="tooltip">
            <button type="button" class="material-symbols-outlined bg-gray-200 answer-comment-delete-btn" style="border: 2px solid black; border-radius: 2px; color: black;" data-comment-id="{{ $comment->id }}" data-answer-id="{{ $comment->answer_id }}">delete</button>
            <p class="tooltiptext">Delete comment</p>
        </div>
    </div>
    @endif
</div>
<div style="display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
    <p style="margin: 0;">{{ $comment->user ? $comment->user->name : '[deleted]' }} at {{ $comment->updated_at }} @if ($comment->edited == 1) (edited) @endif</p>
</div>

<div id="answer_comment_edit_form_{{ $comment->id }}" class="hidden">
    @csrf
    <h5>Edit</h5>
    <input type="text" name="answer_comment_body" id="answer_comment_body_edit_input_{{ $comment->id }}" value="{{ $comment->body }}" required>
    <div class="tooltip">
        <button type="button" class="material-symbols-outlined bg-gray-200" style="border: 2px solid black; border-radius: 2px; color: black;" id="answer_comment_edit_btn_{{ $comment->id }}">check</button>
        <p class="tooltiptext">Submit</p>
    </div>
    <div class="tooltip">
        <button type="button" class="material-symbols-outlined bg-gray-200" style="border: 2px solid black; border-radius: 2px; color: black;" id="answer_comment_cancel_btn_{{ $comment->id }}">cancel</button>
        <p class="tooltiptext">Cancel</p>
    </div>
</div>