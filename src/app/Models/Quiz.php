<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;
    protected $fillable = [
        'code', 'animal', 'video', 'question', 'quiz_question_options', 'options'
    ];

    public function quizOption()
    {
        return $this->hasMany(QuizOption::class);
    }
}
