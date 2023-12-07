<?php
    use App\Models\Question;
    use App\Models\Answer;

    $question = Question::find($answer->question_id);
?>

<li>
    <div id="answer-view-{{ $answer->id }}"  class="answers bg-gray-100 mb-3 p-4">
        <div style="display: flex; flex-direcion: row; gap: 5px;">
            <h4 class="font-bold">{{ $answer->title }}</h4>
            @if (Auth::user()->id === $question->user_id)
            <button type="button" id="validate-answer-btn-{{ $answer->id }}" class="validate_answer_btn" data-id="{{ $answer->id }}">Validate answer</button>
            @endif
            @if ($answer->correct === true)
            <p id="valid_answer_{{ $answer->id }}">this answer has been marked as correct</p>
            @else
            <p id="valid_answer_{{ $answer->id }}" class="hidden">this answer has been marked as correct</p>
            @endif
        </div>
        <p>{{ $answer->content }}</p>
        <div>
            @if ($answer->user_id === Auth::user()->id)
            <a id="edit-answer-btn" class="button bg-blue-500 text-white px-4 py-2 rounded mt-1 inline-block">Edit Answer</a>                  
            <form action="{{ route('answers.delete', ['question_id' => $question->id, 'answer_id' => $answer->id]) }}" method="POST" class="inline-block">
                @csrf
                @method('DELETE')
                <button type="submit" class="button bg-red-500 text-white px-4 py-2 rounded mt-1 inline-block">Delete Answer</button>
            </form>
            @endif
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
</li>