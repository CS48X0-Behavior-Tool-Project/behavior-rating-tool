<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'options'
    ];

    public function attemptQuiz()
    {
        return $this->hasMany(AttemptQuiz::class);
    }
}
