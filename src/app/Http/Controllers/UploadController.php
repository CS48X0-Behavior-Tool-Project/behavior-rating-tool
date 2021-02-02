<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UploadController extends Controller
{

  /**
  * Display the simple html page with the file upload.
  */
    public function getUploadPage() {
      return view ('upload');
    }

    /**
    * Extract data from .csv file.
    */
    public function uploadFile() {
      if (request()->has('mycsv')) {
        $data = array_map('str_getcsv', file(request()->mycsv));
        $header = $data[0];
        unset($data[0]);
        foreach ($data as $value) {
          $firstname = $value[0];
          $surname = $value[1];
          $email = $value[2];
          $id = $value[3];

          $this -> uploadUser($firstname,$surname,$email,$id);
        }
        echo "Upload from .csv successful!";  //output for testing purposes
      }
      else {
        return 'please upload a file';
      }
    }

    /**
    * Upload an individual user to the database.
    */
    public function uploadUser($firstname, $surname, $email, $id) {
      DB::insert('insert into user (id, username, role_id, first_name, last_name, email) values (?, ?, ?, ?, ?, ?)',
      [NULL, strtolower(substr($firstname,0,1).$surname), 2, $firstname, $surname, $email]);
      echo "Uploaded new user!\n";  //output for testing purposes
    }

    // TODO: force incoming csv files to conform to specific format
}
