<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Tag;
use App\Models\Answer;
use App\Models\QuestionImage;
use App\Models\QuestionComment;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Question extends Model
{
    protected $fillable = [
        'title',
        'content',
        'score',
        'edited',
        'user_id',
    ];

    // Relationships
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class); 
    }

    public function answers() : HasMany
    {
        return $this->hasMany(Answer::class);
    }
    
    public function tags() : BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function images()
    {
        return $this->hasMany(QuestionImage::class);
    }

    public function upvoters() : BelongsToMany
    {
        return $this->belongsToMany(User::class, 'question_up_votes', 'question_id', 'user_id');
    }

    public function downvoters() : BelongsToMany
    {
        return $this->belongsToMany(User::class, 'question_down_votes', 'question_id', 'user_id');
    }

    public function comments() : HasMany
    {
        return $this->hasMany(QuestionComment::class);
    }

    public function followers() : BelongsToMany
    {
        return $this->belongsToMany(User::class, 'following_questions', 'question_id', 'user_id');
    }
}
