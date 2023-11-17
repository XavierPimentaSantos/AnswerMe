<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'settings';

    protected $fillable = [
        'dark_mode',
        'hide_nation',
        'hide_birth_date',
        'hide_email',
        'hide_name',
        'language'
    ];
}
