<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Tag;

class Question extends Model
{
    protected $fillable = [
        'title',
        'content',
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
