<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'title',
        'content',
        // Add any other fields you may need
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class); 
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
    
}
