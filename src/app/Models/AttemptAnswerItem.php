<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttemptAnswerItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'attempt_quiz_id', 'behavior_answers', 'interpretation_answers', 'options'
    ];

}
