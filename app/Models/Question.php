<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Tag;
use App\Models\Answer;

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
}
