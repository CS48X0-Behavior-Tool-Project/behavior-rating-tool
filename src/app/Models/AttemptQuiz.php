<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttemptQuiz extends Model
{
    use HasFactory;

    public function attemptAnswerItem()
    {
        return $this->hasOne(AttemptAnswerItem::class);
    }
}
