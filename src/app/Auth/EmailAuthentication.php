<?php

namespace App\Auth;

use App\Models\User;
use Illuminate\Http\Request;

class EmailAuthentication
{
  protected $email;
  protected $identifier = 'email';

  public function __construct($email) {
    $this->email = $email;
  }

  /**
  * Request the new link that is to be sent to the email recipient.
  */
  public function requestLink() {
    $user = $this->getUserByIdentifier($this->email);

    $user->storeToken()->sendEmailLink([
      'email' => $user->email,
      'name' => $user->first_name.' '.$user->last_name
    ]);
  }

  /**
  * Get the user from the database.
  */
  protected function getUserByIdentifier($value) {
    return User::where($this->identifier, $value)->firstOrFail();
  }
}
