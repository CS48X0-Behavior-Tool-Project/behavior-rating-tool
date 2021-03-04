<?php

namespace App\Auth\Traits;

use App\Models\UserLoginToken;
use Illuminate\Support\Str;

use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

trait EmailAuthenticatable
{

  /**
   * Store a new user login token in the 'users_login_tokens' table of the database.
   */
  public function storeToken()
  {

    $this->token()->delete();

    $this->token()->create([
      'token' => Str::random(40)
    ]);

    return $this;
  }

  /**
   * Generate the new email through the Mail facade.
   */
  public function sendEmailLink(array $options)
  {
    // Don't send emails for tests
    if (App::environment('local')) {
      Mail::to($this)->send(new WelcomeMail($this, $options));
    }
  }

  /**
   * Ensure there is only ever one login token for the user.
   */
  public function token()
  {
    return $this->hasOne(UserLoginToken::class);
  }
}
