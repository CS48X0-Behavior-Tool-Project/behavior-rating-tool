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
    * Extract data from .csv file and store into array.
    */
    public function uploadFile() {
      if (request()->has('mycsv')) {
        $data = array_map('str_getcsv', file(request()->mycsv));
        $header = $data[0];
        unset($data[0]);
        return $data;
      }
      else {
        return 'please upload a file';
      }
    }

    // TODO: extract the data from the data array to upload to database
}
