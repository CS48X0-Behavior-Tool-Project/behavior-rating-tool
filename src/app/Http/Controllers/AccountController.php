<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\User;

$email_message;

class AccountController extends Controller
{
  /**
  * Called when /account gets a post request from the forms.
  */
  public function update() {

    global $email_message;

    //need to also check that the field isn't empty otherwise everything is updated to NULL
    if (request()->has('fname') && request()->input('fname') !== NULL) {
      $this->changeFirstName();
    }

    if (request()->has('lname') && request()->input('lname') !== NULL) {
      $this->changeLastName();
    }

    if (request()->has('old-email') && request()->input('old-email') !== NULL) {
      $this->changeEmail();
      return redirect()->route('account_route')->with('email_error_message', $email_message);
    }

    if (request()->has('change_password') && request()->input('old-password') !== NULL) {
      $this->changePassword();
    }

    return redirect()->route('account_route');
  }

  /**
  * Change the first name of the currently logged in user.
  */
  public function changeFirstName() {
    $firstname = request()->input('fname');

    $user = auth()->user();
    $oldfirstname = $user->first_name;

    if ($firstname !== $oldfirstname) {
      DB::table('users')
                ->where('first_name', $oldfirstname)
                ->update(['first_name' => $firstname]);
    }
  }

  /**
  * Change the last name of the currently logged in user.
  */
  public function changeLastName() {
    $lastname = request()->input('lname');

    $user = auth()->user();
    $oldlastname = $user->last_name;

    if ($lastname !== $oldlastname) {
      DB::table('users')
                ->where('last_name', $oldlastname)
                ->update(['last_name' => $lastname]);
    }
  }

  /**
  * Change the email of the currently logged in user.
  */
  public function changeEmail() {

    global $email_message;

    $oldemail = request()->input('old-email');
    $newemail = request()->input('email');

    $user = auth()->user();
    $currentemail = $user->email;

    //make sure the old email they put in matches with what is in the database
    if ($currentemail !== $oldemail) {
      $email_message = "Old Email does not match current email.";
      return;
    }

    //only update the user's email if it's different
    if ($currentemail !== $newemail) {

      if ($this->checkDuplicate($newemail)) {
        $email_message = "That email is already in use.";
        return;
      }

      DB::table('users')
                ->where('email', $currentemail)
                ->update(['email' => $newemail]);
    }
  }

  /**
  * Checks whether an email already exists in our records.
  */
  public function checkDuplicate ($email) {
    return User::where('email', '=', $email)->exists();
  }

  /**
  * Change the password of the currently logged in user.
  */
  public function changePassword() {
    $oldpassword = request()->input('old-password');
    $newpassword = request()->input('password');
    $confirmpassword = request()->input('password_confirmation');

    if (strcmp($newpassword, $confirmpassword) !== 0) {
      // TODO: notify the user that the passwords and the same
      return redirect()->back()->with('mismatched_passwords','The new passwords do not match.');
    }

    // TODO: check if the old password matches what is in the database for this user
    // TODO: update password of currently logged in user in the database to the new password
  }
}
