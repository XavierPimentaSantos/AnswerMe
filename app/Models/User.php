<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'fullname',
        'username',
        'user_password',
        'email',
        'bio',
        'birth_date',
        'nationality',
        'user_type',
        'user_settings'
    ];

    protected $hidden = [
        'user_password'
    ]

    public function settings()
    {
        return $this->belongsTo(Settings::class, 'user_settings');
    }
}
