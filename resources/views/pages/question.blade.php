@extends('layouts.app')

@section('content')

<?php
    $available_tags = DB::table('tags')->get();
    $sel_tags = array();
    $question_tags = array();
    foreach($question->tags as $tag) {
        $question_tags[] = $tag->id;
    }
?>

<article class="card" data-id="{{ $question->id }}">
            <div id = "question-view" class="questions bg-gray-200 mb-3 p-4" style="display: block;">
                <div class="question-card-body">
                    <div class="question-title">
                        <div style="display: flex; flex-direction: row;">
                            <h2>{{ $question->title }}</h2>
                            @if ($question->edited == 1)
                                <h3>(edited)</h3>
                            @endif
                        </div>
                        @if ($question->user_id)
                        <p class="card-content text-red-700">Asked by: {{ $question->user->name }}</p>
                        @endif
                    </div>
                    <p class="card-content">{{ $question->content }}</p>
                </div>
                <div>
                    @if ($question->user_id === auth()->user()->id)                       
                    <a id = "edit-question-btn" class="button bg-blue-500 text-white px-4 py-2 rounded mt-1 inline-block">Edit Question</a>
                    <form action="{{ route('questions.delete', $question->id)}}" method="POST" class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="button bg-red-500 text-white px-4 py-2 rounded mt-1 inline-block">Delete Question</button>
                    </form>
                    @endif
                </div>
                <div id="tag_bar" style="display: flex; flex-direction: row;">
                    @if ($question->tags->count() > 0)
                        @foreach ($question->tags as $tag)
                            <div class="tag_item" style="background-color: #00ffff">
                                {{ $tag->name }}
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
            <div id="question-edit" class="questions bg-gray-200 mb-3 p-4" style="display: none;">
                        <h2>Edit Question</h2>

                        <form id="edit-question-form" action="{{ route('questions.edit', $question->id) }}" method="POST" class="inline-block">
                            @csrf
                            @method('POST')
                            <label for="title">Title:</label>
                            <input type="text" name="title" id = "title-input" value="{{ $question->title }}"  data-id="{{ $question->id }}" required>

                            <label for="content">Content:</label>
                            <input type="text" name="content" value="{{ $question->content }}" required>

                            <div id="tag-section" style="display: flex; flex-direction: row; flex-wrap: wrap; gap: 4px;">
                                @include('partials.selected_tags', ['tags' => []]) 
                            </div>

                            <div class="form-group" id="question_tag_container">
                                <div id="tag_list">
                                    @foreach ($available_tags as $available_tag)
                                        @if (in_array($available_tag->id, $question_tags))
                                        <div style="display: flex; flex-direction: row;">
                                            <input type="checkbox" name="sel_tags[]" id="tag_{{ $available_tag->id }}" value="{{ $available_tag->id }}" class="tag-checkbox" checked>
                                            <label for="tag_{{ $available_tag->id }}">{{ $available_tag->name }}</label>
                                        </div>
                                        @else
                                        <div style="display: flex; flex-direction: row;">
                                            <input type="checkbox" name="sel_tags[]" id="tag_{{ $available_tag->id }}" value="{{ $available_tag->id }}" class="tag-checkbox">
                                            <label for="tag_{{ $available_tag->id }}">{{ $available_tag->name }}</label>
                                        </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                            <button class = "button" type="submit" id="update-question-btn">Update Question</button>
                        </form>
                    </div>
            </article>
@if (Auth::check())
<form action="{{ route('answers.store', $question->id) }}" method="post">
    @csrf
    <h3> Answer this question </h3>
    <label for="title">Title:</label>
    <input type="text" id="title" name="title" required>
    <label for="content">Content:</label>
    <textarea id="content" name="content" rows="4" required></textarea>
    <button type="submit">Create Answer</button>
</form>
@endif
@if ($question->answers->count() > 0)
<article class="card text-center" data-id="{{ $question->id }}">
    <div>
        <h3 class="py-5">Answers:</h3>
        <ol>
            @foreach ($question->answers as $answer)
                <div id = "answer-view"  class="answers bg-gray-100 mb-3 p-4">
                    <li>
                        <h4 class="font-bold">{{ $answer->title }}</h4>
                        <p>{{ $answer->content }}</p>
                        <div>
                        @if ($answer->user_id === auth()->user()->id)
                        <a id = "edit-answer-btn" class="button bg-blue-500 text-white px-4 py-2 rounded mt-1 inline-block">Edit Answer</a>                  
                        <form action="{{ route('answers.delete', ['question_id' => $question->id, 'answer_id' => $answer->id]) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="button bg-red-500 text-white px-4 py-2 rounded mt-1 inline-block">Delete Answer</button>
                            </form>
                        @endif
                        </div>
                    </li>
                </div>
                <div id="answer-edit" style="display: none;"  class="answers bg-gray-100 mb-3 p-4">
                    <h2>Edit Answer</h2>
                        <form id = "edit-answer-form" action="{{ route('answers.edit', ['question_id' => $question->id, 'answer_id' => $answer->id]) }}" method="POST" class="inline-block">
                            @csrf
                            @method('POST')
                            <label for="title">Title:</label>
                                <input type="text" name="title" value="{{ $answer->title }}" required>

                            <label for="content">Content:</label>
                                <input type="text" name="content" value="{{ $answer->content }}" required>

                            <button class = "button" type="submit" id="update-answer-btn">Update Answer</button>
                        </form>
                </div>
            @endforeach
        </ol>
    </div>
    @if ($question->answers->count() > 10)
        <div class="pagination">
            {{ $question->answers()->paginate(10)->links() }}
        </div>
    @endif
    @else
    <h2 class="py-5 text-center">No answers yet!</h2>
</article>
@endif


<script>
    document.getElementById('edit-question-btn').addEventListener('click', function () {
        toggleQuestionSections(true);
    });

    function toggleQuestionSections(editMode) {
        document.getElementById('question-edit').style.display = editMode ? 'block' : 'none';
        document.getElementById('question-view').style.display = editMode ? 'none' : 'block';
    }
</script>


@endsection