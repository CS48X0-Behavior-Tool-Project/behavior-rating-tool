<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizOption extends Model
{
    use HasFactory;
    protected $fillable = [
        'quiz_question_id', 'type', 'title', 'marking_scheme', 'is_solution', 'options'
    ];

}
