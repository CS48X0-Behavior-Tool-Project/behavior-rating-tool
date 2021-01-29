<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
  /**
  * This function will eventually submit the information of the new user being created.
  */
  public function submit(Request $request) {
    return 'new user created';
  }
}
