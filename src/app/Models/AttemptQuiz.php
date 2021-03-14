<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AttemptAnswerItem;


class AttemptQuiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'attempt_id', 'quiz_id'
    ];

    public function attemptAnswerItem()
    {
        return $this->hasOne(AttemptAnswerItem::class);
    }
}
