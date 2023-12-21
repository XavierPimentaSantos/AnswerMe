<?php 
    use App\Models\Tag;
    use App\Models\User;
    use App\Models\Question;

    $tags = DB::table('tags')->get();
    $authors = DB::table('users')
        ->join('questions', 'users.id', '=', 'questions.user_id') 
        ->select('users.*')
        ->distinct()
        ->get();
?>

@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="{{ route('questions.filter')}}" method="post">
            @csrf
            <div class="filter-container flex-row" style="display: flex;">
            <label for="tagFilter"></label>
            <select name="tagFilter" id="tagFilter">
                <option value="" selected>All Tags</option>
                @foreach ($tags as $tag)
                    <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                @endforeach
            </select>
            <select name="authorId" id="authorId">
                <option value="" selected>Select Author</option>
                @foreach ($authors as $author)
                    <option value="{{ $author->id }}">{{ $author->name }}</option>
                @endforeach
            </select>
            <button id="applyFilter">Apply Filter(S)</button>
        </div>
        </form>
        <form action="{{ route('questions.search')}}" method="post">
            @csrf
            <input type="text" name="query" placeholder="Search...">
            <button type="submit">Search</button>
        </form>
        
        <h1>All Questions</h1>

        @foreach ($questions as $question)
            @include('partials.question_card', ['question' => $question])
        @endforeach

        <div class="text-center mt-2">
            <div class="pagination-links">
                {{ $questions->links() }}
            </div>
        </div>
    </div>

@endsection
