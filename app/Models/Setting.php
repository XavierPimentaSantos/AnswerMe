<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;

use Illuminate\Database\Eloquent\Relations\HasOne;

class Setting extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'dark_mode',
        'hide_nation',
        'hide_birth_date',
        'hide_email',
        'hide_name',
    ];

    public function user() : hasOne
    {
        return $this->hasOne(User::class);
    }
}
