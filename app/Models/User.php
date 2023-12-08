<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

// Added to define Eloquent relationships.
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $timestamps  = false;

    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'bio',
        'nationality',
        'user_type',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function isModerator()
{
    return $this->user_type == 3 || $this->user_type == 4;
}
    public function isAdmin()
{
    return $this->user_type == 4;
}

    public function isBlocked()
{
    return $this->user_type == 2;
}

    public function questionUpVotes() : BelongsToMany
    {
        return $this->belongsToMany(Question::class, 'question_up_votes', 'user_id', 'question_id');
    }

    public function questionDownVotes() : BelongsToMany
    {
        return $this->belongsToMany(Question::class, 'question_down_votes', 'user_id', 'question_id');
    }

    public function answerUpVotes() : BelongsToMany
    {
        return $this->belongsToMany(Answer::class, 'answer_up_votes', 'user_id', 'answer_id');
    }

    public function answerDownVotes() : BelongsToMany
    {
        return $this->belongsToMany(Answer::class, 'answer_down_votes', 'user_id', 'answer_id');
    }
}
