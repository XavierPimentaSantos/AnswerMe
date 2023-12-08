<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionComment extends Model
{
    use HasFactory;

    protected $table = 'comments_questions';

    protected $fillable = [
        'body',
        'question_id',
        'edited',
        'user_id',
    ];

    //Relationships

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function question() : BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
