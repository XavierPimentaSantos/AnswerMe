<?php
    use App\Models\Question;
    use App\Models\Answer;
?>

@foreach ($answers as $answer)
<?php
    $question = Question::find($answer->question_id);

    $answercomments = $answer->comments()->latest()->paginate(4);
?>
<li>
    <div id="answer-view-{{ $answer->id }}"  class="answers bg-gray-100 mb-3 p-4">
        <div style="display: flex; flex-direcion: row; gap: 5px;">
            <div id="answer_score_{{ $answer->id }}" data-id="{{ $answer->id }}" style="display: flex; flex-direction: column; width: 5rem; justify-content: space-around;">
                @csrf
                @include ('partials.answer_score', ['answer_id' => $answer->id])
            </div>

            <div style="display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between; width: -webkit-fill-available;">
                <div style="display: flex; flex-direction: row;">
                    <h4 class="font-bold">{{ $answer->title }}</h4>
                    @if ($answer->correct === true)
                    <h4 id="valid_answer_{{ $answer->id }}" class="material-symbols-outlined" style="color: green;">check</h4>
                    @else
                    <h4 id="valid_answer_{{ $answer->id }}" class="material-symbols-outlined" style="display: none; color: green;">check</h4>
                    @endif
                </div>
                <div>
                    @if (Auth::user()->id === $question->user_id && $answer->correct === false)
                    <div class="tooltip">
                        <button type="button" id="validate-answer-btn-{{ $answer->id }}" class="validate_answer_btn material-symbols-outlined bg-gray-200" data-id="{{ $answer->id }}" style="border: 2px solid black; border-radius: 2px; color: green;">check</button>
                        <p class="tooltiptext">Validate</p>
                    </div>
                    @endif
                    @if (Auth::user()->id !== $answer->user_id)
                    <div class="tooltip">
                        <button type="button" class="material-symbols-outlined bg-gray-200" id="question_report_btn" data-question-id="{{ $question->id }}" style="border: 2px solid black; border-radius: 2px; color: red;">report</button>
                        <p class="tooltiptext">Report</p>
                    </div>
                    @endif
                    @if ($answer->user_id === Auth::user()->id || Auth::user()->isModerator())
                    <div class="tooltip">
                        <button type="button" id="edit-answer-btn" class="material-symbols-outlined bg-gray-200" style="border: 2px solid black; border-radius: 2px; color: black;" data-id="{{ $answer->id }}">edit</button>
                        <p class="tooltiptext">Edit answer</p>
                    </div>
                    <form action="{{ route('answers.delete', ['question_id' => $question->id, 'answer_id' => $answer->id]) }}" method="POST" class="inline-block tooltip" style="margin: 0;">
                        @csrf
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="material-symbols-outlined bg-gray-200" style="border: 2px solid black; border-radius: 2px; color: black;">delete</button>
                        <p class="tooltiptext">Delete answer</p>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        <p class="answer_content_p">{{ $answer->content }}</p>
        <div>
        </div>
    </div>

    <div id="answer-edit-{{ $answer->id }}" style="display: none;"  class="answers bg-gray-100 mb-3 p-4">
        <h2>Edit Answer</h2>
        <form id="edit-answer-form" action="{{ route('answers.edit', ['question_id' => $question->id, 'answer_id' => $answer->id]) }}" method="POST" class="inline-block">
            @csrf
            @method('POST')
            <label for="title">Title:</label>
            <input type="text" name="title" value="{{ $answer->title }}" required>
            <label for="content">Content:</label>
            <input type="text" name="content" value="{{ $answer->content }}" required>
            <button class="button" type="submit" id="update-answer-btn">Update Answer</button>
        </form>
    </div>

    <div class="answer-comment-form">
        <input type="text" name="answer_comment_body" id="answer_comment_body_input_{{ $answer->id }}" maxlength="250" placeholder="Leave a comment...">
        <div class="tooltip">
            <button type="button" class="material-symbols-outlined bg-gray-200 answer-comment-post-btn" style="border: 2px solid black; border-radius: 2px; color: black;"data-id="{{ $answer->id }}">send</button>
            <p class="tooltiptext">Comment</p>
        </div>
    </div>

    <div id="comment-section-{{ $answer->id }}">
        @include ('partials.answer_comment_section', ['answercomments' => $answercomments])
    </div>
</li>

@endforeach