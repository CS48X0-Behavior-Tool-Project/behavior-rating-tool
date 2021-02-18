<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    /**
    * This function will eventually submit the login information to be checked in the database for authentication.
    */
    public function submit(Request $request) {
      // $user = $request->user(); //getting the current logged in user
      // dd($user->hasRole('admin','editor')); // and so on

      return 'login confirmed';
    }
}
