<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class NewAccountController extends Controller
{
  /**
  * Called when /confirmation gets a post request from the page.
  */
  public function createAccount() {

    //set this user's password to their choice
    auth()->user()->fill([
              'password' => Hash::make(request()->input('password'))
          ])->save();

    $surveyResponse = request()->input('experience');


    // TODO: make sure we are currently logged in to the proper user (done via email link?)
    // TODO: store survey choice in json object in users tables
    // TODO: find a way to block future access to this page, they only need to do the survey once, and
    //changing password needs to be done on account management page

    return redirect()->route('home_route');
  }
}
