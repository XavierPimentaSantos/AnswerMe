<article class="card" data-id="{{ $question->id }}">
            <div class="questions bg-gray-200 mb-3 p-4">
                <div class="question-card-body">
                <a href="{{ route('questions.show', $question->id) }}">
                        <div class="question-title">
                            <h2>{{ $question->title }}</h2>
                        </div>
                </a>
                    <p class="card-content">{{ $question->content }}</p>
                </div>
                <a href="{{ route('questions.show', $question->id) }}" class="button bg-blue-500 text-white px-4 py-2 rounded mt-2 inline-block">See Answers</a>
            </div>
</article>