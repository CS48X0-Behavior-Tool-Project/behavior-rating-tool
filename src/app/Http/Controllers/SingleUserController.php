<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Api\UserController;

use App\Models\User;
use Illuminate\Support\Facades\Password;

class SingleUserController extends Controller
{
    private $uc;

    public function __construct() {
      $this->uc = new UserController();
    }

    public function action($actionName, $id) {
      if ($actionName == 'reset') {
        $name = $this->resetPassword($id);
        return redirect()->route('users_route')
          ->with('reset-message', 'User ' . $name . ' has been sent a password reset email.');
      }
      else if ($actionName == 'delete') {
        $name = $this->deleteUser($id);
        return redirect()->route('users_route')->with('deletion-message', 'User ' . $name . ' has been deleted.');
      }
    }

    private function resetPassword($id) {
      Password::sendResetLink(['id' => $id]);        //function exists in PasswordBroker class
      $user = $this->uc->getUser($id);
      $name = $user->first_name . ' ' . $user->last_name;
      return $name;
    }

    private function deleteUser($id) {
      $user = $this->uc->getUser($id);
      $name = $user->first_name . ' ' . $user->last_name;
      $this->uc->deleteUser(new Request(), $id);
      return $name;
    }
}
