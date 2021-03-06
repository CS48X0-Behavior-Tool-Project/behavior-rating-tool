<?php

namespace App\Http\Controllers\Api;

use Bouncer;
use App\Models\User;
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
    }
}
