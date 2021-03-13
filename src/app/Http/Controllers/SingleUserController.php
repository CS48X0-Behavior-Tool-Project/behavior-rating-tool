<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SingleUserController extends Controller
{
    public function action($id) {
      if (isset($_POST['reset-password'])) {
        $this->resetPassword($id);
      }
      else if (isset($_POST['delete-user'])) {
        $this->deleteUser($id);
      }
      else if (isset($_POST['view-quizzes'])) {
        $this->viewUserQuizzes($id);
      }
    }

    private function resetPassword($id) {
      echo 'reset password';
    }

    private function deleteUser($id) {
      echo 'delete user';
    }

    private function viewUserQuizzes($id) {
      echo 'view user quizzes';
    }
}
