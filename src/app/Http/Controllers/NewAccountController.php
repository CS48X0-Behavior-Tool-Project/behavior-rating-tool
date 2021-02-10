<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NewAccountController extends Controller
{
  /**
  * Called when /confirmation gets a post request from the page.
  */
  public function createAccount() {
    $surveyResponse = request()->input('experience');
    echo $surveyResponse;

    // TODO: make sure we are currently logged in to the proper user (done via email link?)
    // TODO: change the current default password to the user's new choice
    // TODO: store survey choice in json object in users tables
    // TODO: redirect to home page when finished  (find a way to block future access?)
  }
}
