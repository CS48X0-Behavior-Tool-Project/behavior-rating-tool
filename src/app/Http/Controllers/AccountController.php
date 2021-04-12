<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$name_msg;
$email_msg;
$email_err_msg;
$password_msg;
$password_err_msg;

class AccountController extends Controller
{

  public function __construct()
  {
    $this->middleware('auth');
  }
  /**
   * Called when /account gets a post request from the forms.
   */
  public function update()
  {

    global $name_msg;
    global $email_msg;
    global $email_err_msg;
    global $password_msg;
    global $password_err_msg;

    //need to also check that the field isn't empty otherwise everything is updated to NULL
    if (request()->has('fname') && request()->input('fname') !== NULL) {
      $this->changeFirstName();
    }

    if (request()->has('lname') && request()->input('lname') !== NULL) {
      $this->changeLastName();
    }

    if (request()->has('old-email') && request()->input('old-email') !== NULL) {
      $this->changeEmail();
    }

    if (request()->has('old-password') && request()->input('old-password') !== NULL) {
      $this->changePassword();
    }

    return redirect()->route('account_route')
      ->with('name_message', $name_msg)
      ->with('email_message', $email_msg)
      ->with('email_error', $email_err_msg)
      ->with('password_message', $password_msg)
      ->with('password_error', $password_err_msg);
  }

  /**
   * Change the first name of the currently logged in user.
   */
  public function changeFirstName()
  {

    global $name_msg;

    $firstname = request()->input('fname');

    $user = auth()->user();
    $oldfirstname = $user->first_name;

    if ($firstname !== $oldfirstname) {

      $user->first_name = $firstname;
      $user->save();

      // TODO add an unsuccesful save message

      $name_msg = "Name changed successfully!";
    }
  }

  /**
   * Change the last name of the currently logged in user.
   */
  public function changeLastName()
  {

    global $name_msg;

    $lastname = request()->input('lname');

    $user = auth()->user();
    $oldlastname = $user->last_name;

    if ($lastname !== $oldlastname) {

      $user->last_name = $lastname;
      $user->save();

      $name_msg = "Name changed successfully!";
    }
  }

  /**
   * Change the email of the currently logged in user.
   */
  public function changeEmail()
  {

    global $email_msg;
    global $email_err_msg;

    $oldemail = request()->input('old-email');
    $newemail = request()->input('email');
    $confirmemail = request()->input('email-confirm');

    //make sure the user filled in all the fields
    if ($newemail === NULL || $confirmemail === NULL) {
      $email_err_msg = "Please fill in all the fields.";
      return;
    }

    $user = auth()->user();
    $currentemail = $user->email;

    //make sure the old email they put in matches with what is in the database
    if ($currentemail !== $oldemail) {
      $email_err_msg = "Old Email does not match current email.";
      return;
    }

    //only update the user's email if it's different
    if ($currentemail !== $newemail) {

      if ($this->checkDuplicate($newemail)) {
        $email_err_msg = "That email is already in use.";
        return;
      }

      $user->email = $newemail;
      $user->save();

      $email_msg = "Email changed successfully!";
    }
  }

  /**
   * Checks whether an email already exists in our records.
   */
  public function checkDuplicate($email)
  {
    return User::where('email', '=', $email)->exists();
  }

  /**
   * Change the password of the currently logged in user.
   */
  public function changePassword()
  {

    global $password_msg;
    global $password_err_msg;

    $oldpassword = request()->input('old-password');
    $newpassword = request()->input('password');
    $confirmpassword = request()->input('password-confirm');

    if ($newpassword === NULL || $confirmpassword === NULL) {
      $password_err_msg = "Please fill in all the fields.";
      return;
    }

    $user = auth()->user();

    // check if the old password matches what is in the database for this user, then update to new
    if (Hash::check($oldpassword, $user->password)) {
      $user->fill([
        'password' => Hash::make($newpassword)
      ])->save();

      $password_msg = "Password changed successfully!";
    } else {
      $password_err_msg = "Incorrect Password.";
    }
  }
}
