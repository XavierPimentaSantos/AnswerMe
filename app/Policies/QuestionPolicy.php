<?php

namespace App\Policies;

use App\Models\User;

class QuestionPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    protected $policies = [
        Question::class => QuestionPolicy::class,
    ];

}
