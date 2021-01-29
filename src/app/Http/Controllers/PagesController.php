<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    /**
    * These controller methods simply load up the appropriate views from the pages folder.
    */

    public function getLoginPage() {
      return view('pages.login');
    }

    public function getConfirmationPage() {
      return view('pages.confirmation');
    }

    public function getAddUser() {
      return view('pages.adduser');
    }

    public function getAccountManagement() {
      return view('pages.account');
    }
}
