@extends('layouts.app')
@section('content')
    <div class="container">
        <h2>All Questions</h2>

        @foreach ($questions as $question)
            <div class="card mb-3">
                <div class="card-body">
                <a href="{{ route('questions.show', $question->id) }}">
                        <div class="question-card">
                            <h3>{{ $question->title }}</h3>
                            <!-- Add more details as needed -->
                        </div>
                </a>
                    <p class="card-text">{{ $question->content }}</p>
                    <!-- Add any other details you want to display -->
                </div>
            </div>
        @endforeach

        {{ $questions->links() }} <!-- This will display pagination links -->
    </div>
@endsection
