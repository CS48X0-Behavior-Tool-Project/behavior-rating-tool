<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    /**
    * These controller methods simply load up the appropriate views from the pages folder.
    */

    public function getLoginPage() {
      return view('auth.login');
    }

    public function getHomePage() {
      return view('home');
    }

    public function getConfirmationPage() {
      return view('account_creation');
    }

    public function getAddUser() {
      return view('admin_add_users');
    }

    public function getAccountManagement() {
      return view('account');
    }
}
