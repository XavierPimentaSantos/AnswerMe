<!-- resources/views/partials/selected_tags.blade.php -->

<?php 
    use App\Models\Tag;

    $db_tags = DB::table('tags')->get();
?>

@foreach($tags as $tag_name)
    <?php
        $tag = Tag::where('name', $tag_name);
    ?>
    @if ($tag_name)
    <div style="border: 2px solid red; width: min-width; display: flex; flex-direction: row;" >
        <div>{{ $tag_name }}</div>
        <label for="tag_{{ $tag_name }}">X</label>
    </div>
    @endif
@endforeach
