<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

$count_message;
$add_user_message;
$count_msg_with_duplicates;
$count_msg_no_new;
$add_user_err_message;

class UploadController extends Controller
{
    /**
    * Called when /add_user gets a post request from the forms.
    */
    public function upload() {

      global $count_message;
      global $add_user_message;
      global $count_msg_with_duplicates;
      global $count_msg_no_new;
      global $add_user_err_message;

      if (request()->has('mycsv')) {
        $this->uploadFile();
        return redirect()->route('add_user_route')
          ->with('user_count_message', $count_message)
          ->with('count_message_duplicates', $count_msg_with_duplicates)
          ->with('count_message_no_new', $count_msg_no_new);
      }
      else if (request()->has('add_single_user')) {
        $this->uploadUser();
        return redirect()->route('add_user_route')
        ->with('add_message', $add_user_message)
        ->with('add_message_error', $add_user_err_message);
      }
      else {
        return redirect()->route('add_user_route');
      }
    }

    /**
    * Extract data from .csv file.
    */
    public function uploadFile() {

        global $count_message;
        global $count_msg_with_duplicates;
        global $count_msg_no_new;

        $data = array_map('str_getcsv', file(request()->mycsv));
        $header = $data[0];
        unset($data[0]);

        $new_user_count = 0;
        $duplicate_count = 0;
        $failed_entry_count = 0;

        foreach ($data as $value) {
          $firstname = $value[0];
          $surname = $value[1];
          $email = $value[2];

          if (!($this->validateEmail($email))) {
            $failed_entry_count++;
            continue;
          }

          if ($this->checkDuplicate($email)) {
            $duplicate_count++;
            continue;
          }

          $this->dbInsert($firstname,$surname,$email,'student',2);

          $new_user_count++;
        }

        $count_message = $new_user_count.' new users added!';

        if ($new_user_count === 0) {
          $count_msg_no_new = $count_message.' All the emails are already in the system.';
          $count_message = NULL;
        }
        else if ($duplicate_count > 0) {
          $count_message .= ' '.$duplicate_count.' email(s) already exist.';
          $count_msg_with_duplicates = $count_message;
          $count_message = NULL;
        }

        if ($failed_entry_count > 0) {
          $count_msg_with_duplicates .= ' '.$failed_entry_count.' entries were invalid.';
        }
    }

    /**
    * Ensure that the email field is a valid email format.
    */
    public function validateEmail($email) {
      return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
    * Upload a single user from the form.
    */
    public function uploadUser() {

      global $add_user_message;
      global $add_user_err_message;

      $firstname = request()->input('fname');
      $surname = request()->input('lname');
      $email = request()->input('email');
      $role = request()->input('role');

      if ($this->checkDuplicate($email)) {
        $add_user_err_message = "This email already exists.";
        return;
      }

      $add_user_message = "New user added!";

      $role_id = 0;

      switch ($role) {
        case "admin":
          $role_id = 1;
          break;
        case "student":
          $role_id = 2;
          break;
        case "expert":
          $role_id = 3;
          break;
        case "ta":
          $role_id = 4;
          break;
      }

      $this->dbInsert($firstname,$surname,$email,$role,$role_id);
    }

    /**
    * Checks whether an email already exists in our records.
    */
    public function checkDuplicate ($email) {
      return User::where('email', '=', $email)->exists();
    }

    /**
    * Insert new user data into the database.
    */
    public function dbInsert($firstname, $surname, $email, $role, $role_id) {
      $username = strtolower(substr($firstname,0,1).$surname);    //needed for now, until we get rid of registration tab

      $user = new User();
      // TODO: after account creation page, change the default password to something more secure
      $user->password = Hash::make('password');
      $user->email = $email;
      $user->name = $username;     //needed for now, until we get rid of registration tab
      $user->first_name = $firstname;
      $user->last_name = $surname;
      $user->options = json_encode((object)[]);
      $user->save();

      //create role in roles table if it doesn't already exist
      DB::insert('insert ignore into roles (id, name)
      values (?,?)',
      [$role_id,$role]);

      //link the new user to their role
      DB::insert('insert into users_roles (user_id, role_id)
      values (?,?)',
      [$user->id, $role_id]);
    }

    // TODO: force incoming csv files to conform to specific format
    // TODO: allow different file types
}