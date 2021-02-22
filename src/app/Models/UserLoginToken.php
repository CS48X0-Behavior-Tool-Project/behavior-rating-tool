<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class UserLoginToken extends Model
{
    use HasFactory;

    const TOKEN_EXPIRY = 3;       //the email link will expire in 3 days

    protected $table = 'users_login_tokens';

    protected $fillable = [
      'token'
    ];

    public function getRouteKeyName() {
      return 'token';
    }

    public function isExpired() {
      return $this->created_at->diffInDays(Carbon::now()) > self::TOKEN_EXPIRY;
    }

    public function scopeExpired($query) {
      return $query->where('created_at', '<', Carbon::now()->subDays(self::TOKEN_EXPIRY));
    }

    public function user() {
      return $this->belongsTo(User::class);
    }
}
