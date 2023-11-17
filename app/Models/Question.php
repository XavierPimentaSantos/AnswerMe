<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\Post;

class Question extends Model
{
    use HasFactory;

    protected $primaryKey = 'post_id';
    public $timestamps = false;
    protected $attributes = [
        'title' = "Question title.",
        'body' = "Question body goes here.",
        'score' = 0,
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
