<!-- resources/views/partials/selected_tags.blade.php -->

<?php 
    use App\Models\Tag;

    $db_tags = DB::table('tags')->get();
?>

@foreach($tags as $tagID)
    <?php
        $tag = Tag::findOrFail($tagID);
        $tag_name = $tag->name;
    ?>
    <div style="border: 2px solid red; width: min-width; display: flex; flex-direction: row;" >
        <div>{{ $tag_name }}</div>
        <label for="tag_{{ $tagID }}">X</label>
    </div>
@endforeach
