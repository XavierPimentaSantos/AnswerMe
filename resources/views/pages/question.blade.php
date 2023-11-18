
@section('content')

<div id="question_main" data-id="{{ $question->post_id }}">
    <h4 class="question_title">{{ $question->title }}</h4>
    <p class="question_body">{{ $question->body }}</p>
    
</div>