<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\QuestionImage;
use App\Models\Question;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class QuestionImage extends Model
{
    protected $guarded = [];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
