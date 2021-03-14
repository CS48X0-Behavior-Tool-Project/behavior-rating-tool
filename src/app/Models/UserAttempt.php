<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'attempt_id', 'scores', 'options'
    ];

    public function attempt()
    {
        return $this->hasMany(Attempt::class);
    }
}
