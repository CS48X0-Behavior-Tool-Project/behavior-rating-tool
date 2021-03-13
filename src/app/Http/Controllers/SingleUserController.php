<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SingleUserController extends Controller
{
    public function action($id) {
      echo 'User id:  ' . $id;
    }
}
