<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

$count_message;

class UploadController extends Controller
{
    /**
    * Called when /add_user gets a post request from the forms.
    */
    public function upload() {

      global $count_message;

      if (request()->has('mycsv')) {
        $this->uploadFile();
        return redirect()->route('add_user_route')->with('user_count_message', $count_message);
      }
      else if (request()->has('add_single_user')) {
        $this->uploadUser();
        return redirect()->route('add_user_route')->with('add_message', 'New user added!');
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

        $data = array_map('str_getcsv', file(request()->mycsv));
        $header = $data[0];
        unset($data[0]);

        $new_user_count = 0;

        foreach ($data as $value) {
          $firstname = $value[0];
          $surname = $value[1];
          $email = $value[2];

          $this->dbInsert($firstname,$surname,$email,2);

          $new_user_count++;
        }

        $count_message = $new_user_count.' new users added!';
    }

    /**
    * Upload a single user from the form.
    */
    public function uploadUser() {

      $firstname = request()->input('fname');
      $surname = request()->input('lname');
      $email = request()->input('email');
      $role = request()->input('role');

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

      $this->dbInsert($firstname,$surname,$email,$role_id);
    }

    /**
    * Insert new user data into the database.   CURRENTLY DOESN"T SUPPORT ROLE ID.
    */

    //OLD FUNCTION
    /*public function dbInsert($firstname, $surname, $email, $role_id) {
      DB::insert('insert into user (id, name, password, role_id, first_name, last_name, email)
      values (?, ?, ?, ?, ?, ?, ?)',
      [NULL, strtolower(substr($firstname,0,1).$surname), 'password', $role_id, $firstname, $surname, $email]);
    }*/

    //NEW FUNCTION auto registers new users allowing us to log in to them
    public function dbInsert($firstname, $surname, $email, $role_id) {
      $username = strtolower(substr($firstname,0,1).$surname);

      $user = new User();
      $user->password = Hash::make('password');
      $user->email = $email;
      $user->name = $username;
      $user->save();

      //update the first_name and last_name columns of the newly added user
      DB::table('users')
                ->where('name', $username)
                ->update(['first_name' => $firstname, 'last_name' => $surname]);
    }

    // TODO: force incoming csv files to conform to specific format
    // TODO: allow different file types
}
