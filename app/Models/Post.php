<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    use HasFactory;

    const CREATED_AT = 'creation_date';
    const UPDATED_AT = 'edit_date';

    protected $dateFormat = 'U';

    protected $attributes = [
        'id',
        'creation_date',
        'edit_date',
        'edited' = 0,
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
