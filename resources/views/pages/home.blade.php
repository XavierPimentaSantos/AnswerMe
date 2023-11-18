@extends('layouts.app')
@section('content')
    <div class="container">
        <h2>All Questions</h2>

        @foreach ($questions as $question)
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">{{ $question->title }}</h5>
                    <p class="card-text">{{ $question->content }}</p>
                    <!-- Add any other details you want to display -->
                </div>
            </div>
        @endforeach

        {{ $questions->links() }} <!-- This will display pagination links -->
    </div>
@endsection
