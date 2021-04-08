<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class NewAccountController extends Controller
{
  /**
   * Called when /confirmation gets a post request from the page.
   */
  public function createAccount()
  {

    //set this user's password to their choice
    auth()->user()->fill([
      'password' => Hash::make(request()->input('password'))
    ])->save();

    //store survey response as json object in users db table
    $surveyResponse = request()->input('experience');

    //store graduation year as json object in users db table
    $gradYear = request()->input('year');

    $user = auth()->user();
    $jsonobj = json_decode($user->options, true);
    $jsonobj['experience'] = $surveyResponse;
    if ($gradYear != "") {
      $jsonobj['grad_year'] = $gradYear;
    }
    $user->options = json_encode($jsonobj);
    $user->save();

    // TODO: make sure we are currently logged in to the proper user (done via email link?)
    // TODO: find a way to block future access to this page, they only need to do the survey once, and
    // changing password needs to be done on account management page

    return redirect()->route('quizzes_route');
  }
}
