<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    const EMAIL_VALIDATION = 'required|email';
    const PASSWORD_VALIDATION = 'required|min:8';

    public function login(Request $request)
    {
        $loginData = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (auth()->attempt($loginData)) {
            $token = auth()->user()->createToken('authToken')->accessToken;
            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
}