<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\AnswerImage;
use App\Models\Answer;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AnswerImage extends Model
{
    protected $guarded = [];

    public function answer()
    {
        return $this->belongsTo(Answer::class);
    }
}
