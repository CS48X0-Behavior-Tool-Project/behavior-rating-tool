<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
      }
      else {
        return 'please upload a file';
      }
    }

    /**
    * Upload an individual user to the database.  Currently only prints out the user info.
    */
    public function uploadUser($firstname, $surname, $email, $id) {
      echo "Upload user ".$firstname." ".$surname."\temail: ".$email."\tid: ".$id."\n";
    }

    // TODO: database interaction with uploadUser method
    // TODO: force incoming csv files to conform to specific format
}
