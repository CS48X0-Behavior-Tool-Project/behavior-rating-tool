<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    /**
    * This function will eventually submit the login information to be checked in the database for authentication.
    */
    public function submit(Request $request) {
      return 'login confirmed';
    }
}
