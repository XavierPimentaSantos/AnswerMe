@extends('layouts.app')
@section('content')
    <div class="container">
        <h1 class>All Questions</h2>

        @foreach ($questions as $question)
            <div class="questions bg-gray-200 mb-3 p-4">
                <div class="question-card-body">
                <a href="{{ route('questions.show', $question->id) }}">
                        <div class="question-title">
                            <h2>{{ $question->title }}</h2>
                            @if ($question->user_id)
                            <p class="card-content text-red-700">Asked by: {{ $question->user->name }}</p>
                            @endif
                        </div>
                </a>
                    <p class="card-content">{{ $question->content }}</p>
                </div>
                <a href="{{ route('questions.show', $question->id) }}" class="button bg-blue-500 text-white px-4 py-2 rounded mt-2 inline-block">See Answers</a>
            </div>
        @endforeach

        <div class="text-center mt-2 ">
            <div class="pagination-links">
                {{ $questions->links() }}
            </div>
        </div>
    </div>
@endsection