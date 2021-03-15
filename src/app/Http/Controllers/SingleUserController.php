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

    public function action($id) {
      if (isset($_POST['reset-password'])) {
        $name = $this->resetPassword($id);
        return redirect()->route('users_route')
          ->with('reset-message', 'User ' . $name . ' has been sent a password reset email.');
      }
      else if (isset($_POST['delete-user'])) {
        $name = $this->deleteUser($id);
        return redirect()->route('users_route')->with('deletion-message', 'User ' . $name . ' has been deleted.');
      }
      else if (isset($_POST['view-quizzes'])) {
        $this->viewUserQuizzes($id);
      }
    }

    private function resetPassword($id) {
      Password::sendResetLink(['id' => $id]);        //function exists in PasswordBroker class
      $user = $this->uc->getUser($id);
      $name = $user->first_name . ' ' . $user->last_name;
      return $name;
    }

    private function deleteUser($id) {
      // TODO: request that the admin confirm that they want to delete the user
      $user = $this->uc->getUser($id);
      $name = $user->first_name . ' ' . $user->last_name;
      $this->uc->deleteUserAttempts($id);
      $this->uc->deleteUser(new Request(), $id);
      return $name;
    }

    private function viewUserQuizzes($id) {
      // TODO: will be done after the MVP
      echo 'view user quizzes';
    }
}
