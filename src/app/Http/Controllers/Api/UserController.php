<?php

namespace App\Http\Controllers\Api;

use Bouncer;
use App\Models\User;
use App\Models\Attempt;
use App\Models\AttemptAnswerItem;
use App\Models\AttemptQuiz;
use App\Models\UserAttempt;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
    public function getAllUsers()
    {
        // Implement logic to fetch all users
        $users = User::all();
        return $users;
    }

    public function getUser($id)
    {
        return User::find($id);
    }

    public function getUserAttempts($id)
    {
        // Implement logic to fetch user quiz attempts
    }

    public function createUser(Request $request)
    {
    }

    public function updateUser(Request $request, $id)
    {
        $user = $request->user();
        if (Bouncer::is($user)->an('admin')) {
            $toDelete = User::find($id);
            $toDelete->delete();
            return redirect('/')->with('message', 'Delete succeeded!');
        } else {
            return redirect('/')->with('message', 'Delete failed!');
        }
    }

    public function deleteUser(Request $request, $id)
    {
        // Implement logic to delete user
        User::find($id)->delete();
    }

    /**
    *  Delete every attempt the user has made, in preparation for the deletion of the user.
    */
    public function deleteUserAttempts($id)
    {
        $attemptIDs = Attempt::where('user_id','=',$id)->pluck('id')->toArray();
        foreach ($attemptIDs as $key => $value) {
          $attemptQuiz = AttemptQuiz::where('attempt_id','=',$value);
          $attemptQuizID = $attemptQuiz->pluck('id')->toArray()[0];
          AttemptAnswerItem::where('attempt_quiz_id','=',$attemptQuizID)->delete();
          $attemptQuiz->delete();
        }

        Attempt::where('user_id','=',$id)->delete();
        UserAttempt::where('user_id','=',$id)->delete();
    }
}
