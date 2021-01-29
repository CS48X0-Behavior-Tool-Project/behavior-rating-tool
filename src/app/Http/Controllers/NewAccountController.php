<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NewAccountController extends Controller
{
  /**
  * This function will eventually create the password for a newly made account.
  */
  public function submit(Request $request) {
    return 'new account confirmed';
  }
}
