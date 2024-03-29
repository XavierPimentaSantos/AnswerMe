@extends('layouts.app')

@section('content')

<?php
    $available_tags = DB::table('tags')->pluck('name')->toArray();
    $sel_tags = array();
    $question_tags = array();
    foreach($question->tags as $tag) {
        $question_tags[] = $tag->name;
    }  

    $followed;

    if( Auth::check() && Auth::user()->followedQuestions()->where('question_id', $question->id)->exists()) {
        $followed = TRUE;
    }
    else {
        $followed = FALSE;
    }

    $answers = $question->answers()->orderByDesc('score')->orderBy('correct', 'desc')->get();
    $questioncomments = $question->comments()->latest()->paginate(4);
?>

<article class="card" data-id="{{ $question->id }}">
    <div id = "question-view" class="questions bg-gray-200 m-6 p-4" style="display: block;">
        <div class="question-card-body">
            <div class="question-title" style="display: flex; flex-direction: row;">
                <div id="question_score" style="display: flex; flex-direction: column; width: 5rem; justify-content: space-around;">
                    @csrf
                    @include ('partials.question_score', ['question_id' => $question->id])
                </div>
                
                <div style="display: flex; flex-direction: row; align-items: center; justify-content: space-between; width: -webkit-fill-available;">
                    <h2 style="margin: 0; margin-right: 5px;">{{ $question->title }}@if ($question->edited == 1) (edited) @endif</h2>

                    <div style="display: flex; flex-direction: row;">
                        @if (Auth::check() && $question->user_id === Auth::user()->id)
                        <div class="tooltip">
                            <button type="button" id="edit-question-btn" class="material-symbols-outlined bg-gray-200" style="border: 2px solid black; border-radius: 2px; color: black;">edit</button>
                            <p class="tooltiptext">Edit question</p>
                        </div>
                        <form action="{{ route('questions.delete', $question->id)}}" method="POST" class="inline-block tooltip" style="margin: 0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="material-symbols-outlined bg-gray-200" style="border: 2px solid black; border-radius: 2px; color: black;">delete</button>
                            <p class="tooltiptext">Delete question</p>
                        </form>
                        @else
                        <div class="tooltip">
                            <button type="button" class="material-symbols-outlined bg-gray-200" id="question_report_btn" data-question-id="{{ $question->id }}" style="border: 2px solid black; border-radius: 2px; color: red;">report</button>
                            <p class="tooltiptext">Report</p>
                        </div>
                        <div class="tooltip">
                            <button type="button" class="material-symbols-outlined bg-gray-200" id="question_follow_btn" data-question-id="{{ $question->id }}" style="border: 2px solid black; border-radius: 2px; @if ($followed) color: green; @else color: black; @endif">notifications</button>
                            <p id="follow_btn_tooltip" class="tooltiptext">@if ($followed) Unfollow @else Follow @endif</p>
                        </div>
                        @endif
                    </div>
                    
                </div>
            </div>
            @if ($question->user)
            <a href="{{ route('profile.showUser', $question->user->username) }}" class="card-content text-red-700" style="margin-left: 5rem; margin-top: -3rem;"><h5 style="margin-left: 5rem; margin-top: -3rem; margin-bottom: 0; width: fit-content;">{{ $question->user->username }}</h5></a>
            @else
            <h3 class="card-content text-red-700" style="margin-left: 5rem;">[deleted]</h3>
            @endif
            <p class="card-content" style="margin-bottom: 0.8rem;">{{ $question->content }}</p>

            <div id="tag_bar" style="display: flex; flex-direction: row; gap: 4px; flex-wrap: wrap; align-content: center;">
                @if ($question->tags->count() > 0)
                    @foreach ($question->tags as $tag)
                        <div class="tag_item" style="border: 2px solid black; border-radius: 2px; padding: 2px 4px; height: fit-content; width: fit-content;">
                            {{ $tag->name }}
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <div id="images-container" class="m-2 flex overflow-x-auto">
            @foreach($question->images as $image)
                <img src="{{ asset($image->picture_path) }}" alt="Question Image"  style = "max-width: 15%;  padding: 5px">
            @endforeach
        </div>
    </div>
    <div id="question-edit" class="questions bg-gray-200 mb-3 p-4" style="display: none;">
        <h2>Edit Question</h2>

        <form id="edit-question-form" action="{{ route('questions.edit', $question->id) }}" method="POST" class="inline-block" enctype="multipart/form-data">
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
            <div class="form-group">
                <label for="images">Edit Images (Up to 3):</label>
                <input type="file" name="images[]" id="image1" accept="image/*" class="mt-1 p-2 border rounded-md">
                <input type="file" name="images[]" id="image2" accept="image/*" class="mt-1 p-2 border rounded-md">
                <input type="file" name="images[]" id="image3" accept="image/*" class="mt-1 p-2 border rounded-md">
                <div id="images-container" class="m-2 flex overflow-x-auto">
                    @foreach($question->images as $key => $image)
                        <div id="image-{{ $key + 1 }}" class="p-2">
                            <img src="{{ asset($image->picture_path) }}" alt="Question Image" style="width: 100px; padding: 5px">
                        </div>
                    @endforeach
                </div>
            </div>


            <button class = "button" type="submit" id="update-question-btn">Update Question</button>
        </form>
    </div>
</article>

@if (Auth::check())
<div id="question_comment_form">
    @csrf
    <input type="text" name="question_comment_body" id="question_comment_body" maxlength="250" required placeholder="Leave a comment...">
    <div class="tooltip">
        <button type="button" class="material-symbols-outlined bg-gray-200" id="question-comment-post-btn" data-question-id="{{ $question->id }}" style="border: 2px solid black; border-radius: 2px; color: black;">send</button>
        <p class="tooltiptext">Comment</p>
    </div>
</div>
@endif

<div id="comment-section">
    @csrf
    @include ('partials.comment_section', ['questioncomments' => $questioncomments])
</div>

@if (Auth::check() && Auth::user()->id!==$question->user_id)
<form id="answer_post_form">
    @csrf
    <h3> Answer this question </h3>
    <label for="answer-title-input">Title:</label>
    <input type="text" id="answer-title-input" name="answer-title-input" required>
    <label for="answer-content-input">Content:</label>
    <textarea id="answer-content-input" name="answer-content-input" rows="4" required></textarea>
    <button type="button" id="answer-post-btn" data-question-id="{{ $question->id }}">Create Answer</button>
</form>
@endif

<article id="question_answers" class="card text-center" data-id="{{ $question->id }}">
    @if ($answers->count() == 0)
    <h2 class="py-5 text-center" id="no_answers">No answers yet!</h2>
    <div id="has_answers" class="hidden">
    @else
    <div id="has_answers">
    @endif
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

@endsection