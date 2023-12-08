@extends('layouts.app')

@section('content')

<?php
    $available_tags = DB::table('tags')->pluck('name')->toArray();
    $sel_tags = array();
    $question_tags = array();
    foreach($question->tags as $tag) {
        $question_tags[] = $tag->name;
    }

    $answers = $question->answers()->orderByDesc('score')->orderBy('correct', 'desc')->get();
    $questioncomments = $question->comments()->latest()->get(); // paginate aqui?
?>

<article class="card" data-id="{{ $question->id }}">
    <div id = "question-view" class="questions bg-gray-200 mb-3 p-4" style="display: block;">
        <div class="question-card-body">
            <div class="question-title" style="display: flex; flex-direction: row;">
                <div id="question_score" style="display: flex; flex-direction: column;">
                    @csrf
                    @include ('partials.question_score', ['question_id' => $question->id])
                </div>
                
                <div style="display: flex; flex-direction: row;">
                    <h2 style="margin: 0; margin-right: 5px;">{{ $question->title }}</h2>
                    @if ($question->edited == 1)
                        <h3 style= "margin: 0; align-self: center;">(edited)</h3>
                    @endif
                    <div id="tag_bar" style="display: flex; flex-direction: row; gap: 4px; flex-wrap: wrap; align-content: center;">
                        @if ($question->tags->count() > 0)
                            @foreach ($question->tags as $tag)
                                <div class="tag_item" style="border: 2px solid black; border-radius: 3px; padding: 2px 4px; height: fit-content; width: fit-content;">
                                    {{ $tag->name }}
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                @if ($question->user_id)
                <p class="card-content text-red-700">Asked by: {{ $question->user->name }}</p>
                @endif
            </div>
            <p class="card-content">{{ $question->content }}</p>
        </div>
        <div>
            @if (Auth::check() && $question->user_id === auth()->user()->id)                          
            <a id = "edit-question-btn" class="button bg-blue-500 text-white px-4 py-2 rounded mt-1 inline-block">Edit Question</a>
            <form action="{{ route('questions.delete', $question->id)}}" method="POST" class="inline-block">
                @csrf
                @method('DELETE')
                <button type="submit" class="button bg-red-500 text-white px-4 py-2 rounded mt-1 inline-block">Delete Question</button>
            </form>
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
                @csrf
                @include('partials.selected_tags', ['tags' => $question_tags]) 
            </div>

            <div id="tag_list">
                @foreach ($available_tags as $available_tag)
                    <div style="display: none;">
                        @if (in_array($available_tag, $question_tags))
                        <input type="checkbox" name="sel_tags[]" id="tag_{{ $available_tag }}" value="{{ $available_tag }}" class="tag-checkbox" checked>
                        @else
                        <input type="checkbox" name="sel_tags[]" id="tag_{{ $available_tag }}" value="{{ $available_tag }}" class="tag-checkbox">
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="form-group" id="question_tag_container">
                <input type="text" name="tag_input" id="tag_input" list="tag_listing">
                <datalist id="tag_listing">
                    @foreach ($available_tags as $available_tag)
                        <option value="{{ $available_tag }}">{{ $available_tag }}</option>
                    @endforeach
                </datalist>
                <button id="add_tag" type="button">Add tag</button>
            </div>

            <button class = "button" type="submit" id="update-question-btn">Update Question</button>
        </form>
    </div>
</article>

<div id="comment-section">
    @csrf
    @include ('partials.comment_section', ['questioncomments' => $questioncomments])
</div>

@if (Auth::check())
<div id="question_comment_form">
    @csrf
    <h5>Leave a comment</h5>
    <input type="text" name="question_comment_body" id="question_comment_body" required>
    <button type="button" id="question-comment-post-btn" data-question-id="{{ $question->id }}">Post comment</button>
</div>

<div id="answer_post_form">
    @csrf
    <h3> Answer this question </h3>
    <label for="answer-title-input">Title:</label>
    <input type="text" id="answer-title-input" name="answer-title-input" required>
    <label for="answer-content-input">Content:</label>
    <textarea id="answer-content-input" name="answer-content-input" rows="4" required></textarea>
    <button type="button" id="answer-post-btn" data-question-id="{{ $question->id }}">Create Answer</button>
</div>
@endif

@if ($answers->count() > 0)
<article id="question_answers" class="card text-center" data-id="{{ $question->id }}">
    <div>
        <h3 class="py-5">Answers:</h3>
        <ol style="list-style-type: none;" id="answer-section">
            @include ('partials.answer', ['answers' => $answers])
        </ol>
    </div>
    @if ($answers->count() > 10)
    <div class="pagination">
        {{ $answers->paginate(10)->links() }}
    </div>
    @endif
</article>
@else
<h2 class="py-5 text-center">No answers yet!</h2>
<article id="question_answers" class="card text-center" data-id="{{ $question->id }}" style="display: none;">
    <div>
        <h3 class="py-5">Answers:</h3>
        <ol style="list-style-type: none;" id="answer-section">
            @include ('partials.answer', ['answers' => $answers])
        </ol>
    </div>
    @if ($answers->count() > 10)
    <div class="pagination">
        {{ $answers->paginate(10)->links() }}
    </div>
    @endif
</article>
@endif

@endsection