<?php
    use App\Models\Question;
    use App\Models\Answer;
?>

@foreach ($answers as $answer)
<?php
    $question = Question::find($answer->question_id);
?>
<li>
    <div id="answer-view"  class="answers bg-gray-100 mb-3 p-4">
        <div style="display: flex; flex-direction: row; gap: 5px;">
            <div id="answer_score_{{ $answer->id }}" data-id="{{ $answer->id }}" style="display: flex; flex-direction: column;">
                @csrf
                @include ('partials.answer_score', ['answer_id' => $answer->id])
            </div>
            <h4 class="font-bold">{{ $answer->title }}</h4>
            @if (Auth::user()->id === $question->user_id && $answer->correct === false)
            <button type="button" id="validate-answer-btn-{{ $answer->id }}" class="validate_answer_btn" data-id="{{ $answer->id }}">Validate answer</button>
            @endif
            @if ($answer->correct === true)
            <p id="valid_answer_{{ $answer->id }}">this answer has been marked as correct</p>
            @else
            <p id="valid_answer_{{ $answer->id }}" class="hidden">this answer has been marked as correct</p>
            @endif
        </div>
        <p>{{ $answer->content }}</p>
        <div id="answer-images-container" class="m-2 flex overflow-x-auto">
            @if(isset($answer->images))
                @foreach($answer->images as $image)
                    <img src="{{ asset($image->picture_path) }}" alt="Answer Image" style="width: 100px; padding: 5px">
                @endforeach
            @endif
        </div>
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

    <div id="answer-edit" style="display: none;"  class="answers bg-gray-100 mb-3 p-4">
        <h2>Edit Answer</h2>
        <form id="edit-answer-form" action="{{ route('answers.edit', ['question_id' => $question->id, 'answer_id' => $answer->id]) }}" method="POST" class="inline-block" enctype="multipart/form-data">
            @csrf
            @method('POST')
            <label for="title">Title:</label>
            <input type="text" name="title" value="{{ $answer->title }}" required>
            <label for="content">Content:</label>
            <input type="text" name="content" value="{{ $answer->content }}" required>
            <div class="form-group">
                <label for="images">Images (Up to 3):</label>
                <input type="file" name="images[]" id="image1" accept="image/*" class="mt-1 p-2 border rounded-md">
                <input type="file" name="images[]" id="image2" accept="image/*" class="mt-1 p-2 border rounded-md">
                <input type="file" name="images[]" id="image3" accept="image/*" class="mt-1 p-2 border rounded-md">
                <div id="image-preview-container" class="mt-2 flex space-x-2">
                    <div id="image-1" class="p-2"></div>
                    <div id="image-2" class="p-2"></div>
                    <div id="image-3" class="p-2"></div>
                    </div>
                </div>
                <button class="button" type="submit" id="update-answer-btn">Update Answer</button>
        </form>
        
    </div>
    <div class="answer-comment-form">
        <input type="text" name="answer_comment_body" id="answer_comment_body_input_{{ $answer->id }}">
        <button type="button" class="answer-comment-post-btn" data-id="{{ $answer->id }}">Comment</button>
    </div>

    <div id="comment-section-{{ $answer->id }}">
        @include ('partials.answer_comment_section', ['answer_id' => $answer->id])
    </div>
</li>

@endforeach