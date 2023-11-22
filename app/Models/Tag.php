<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Question;

class Tag extends Model
{
    use HasFactory;

    protected $timestamps = false;

    protected $fillable = [
        'name',
    ]

    /*a tag "belongs" to various posts*/
    public function questions() : BelongsToMany
    {
        return $this->belongsToMany(Question::class);
    }
}
