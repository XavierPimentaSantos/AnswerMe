@extends('layouts.app')
@section('content')
    <div class="container">
        <h1 class>All Questions</h2>

        @foreach ($questions as $question)
            @include ('partials.question_card', ['question' => $question])
        @endforeach

        <div class="text-center mt-2 ">
            <div class="pagination-links">
                {{ $questions->links() }}
            </div>
        </div>
    </div>
@endsection
