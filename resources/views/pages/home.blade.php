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
                            <!-- Add more details as needed -->
                        </div>
                </a>
                    <p class="card-content">{{ $question->content }}</p>
                </div>
                <a href="{{ route('questions.show', $question->id) }}" class="button bg-blue-500 text-white px-4 py-2 rounded mt-2 inline-block">See Answers</a>
            </div>
        @endforeach

        {{ $questions->links() }} <!-- This will display pagination links -->
    </div>
@endsection
