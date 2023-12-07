<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Answer extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'correct',
        'score',
        'edited',
        'question_id',
        'user_id',
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function question() : BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function upvoters() : BelongsToMany
    {
        return $this->belongsToMany(User::class, 'answer_up_votes', 'answer_id', 'user_id');
    }

    public function downvoters() : BelongsToMany
    {
        return $this->belongsToMany(User::class, 'answer_down_votes', 'answer_id', 'user_id');
    }
}
