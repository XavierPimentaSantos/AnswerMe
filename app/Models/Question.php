<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Post;

class Question extends Post
{
    use HasFactory;

    protected $primaryKey = 'post_id';
    public $timestamps = false;
    protected $attributes = [
        'title' = "Question title"
    ]
}
