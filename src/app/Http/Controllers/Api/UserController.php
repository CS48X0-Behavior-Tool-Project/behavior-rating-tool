<?php

namespace App\Http\Controllers\Api;

use Bouncer;
use App\Models\User;
use App\Models\Attempt;
use App\Models\UserAttempt;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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
        // Implement logic to fetch user
    }

    public function getUserAttempts($id)
    {
        // Implement logic to fetch user quiz attempts
        $attempts = DB::select('select * from user_attempts where user_id = ?', [$id]);
        Log::info($attempts);
        return $attempts;
    }

    public function createUserAttempts(Request $request)
    {
        // Implement logic to create user quiz attempts

        Log::info($request->route('id'));
        $attempt = new Attempt;
        $attempt->user_id = $request->route('id');
        $attempt->save();
        $attemptId = $attempt->id;

        $userAttempt = new UserAttempt;
        $userAttempt->user_id = $request->route('id');
        $userAttempt->attempt_id = $attemptId;
        if($request->scores) {
            $userAttempt->scores = $request->scores;
        }
        $userAttempt->save();

        return response()->json(['success' => true, 'attempt_id' => $attemptId], 200);
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
    }
}
