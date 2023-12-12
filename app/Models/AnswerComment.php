<?php

namespace App\Models;

use App\Models\User;
use App\Models\Answer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnswerComment extends Model
{
    use HasFactory;

    protected $table = 'comments_answers';

    protected $fillable = [
        'body',
        'answer_id',
        'edited',
        'user_id',
    ];

    // Relationships

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function answer() : BelongsTo
    {
        return $this->belongsTo(Answer::class);
    }
}
