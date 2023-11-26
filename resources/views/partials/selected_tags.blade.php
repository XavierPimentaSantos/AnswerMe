<!-- resources/views/partials/selected_tags.blade.php -->
@foreach($selectedTags as $tag)
    <div style="border: 2px solid red;">{{ $tag }}</div>
@endforeach
