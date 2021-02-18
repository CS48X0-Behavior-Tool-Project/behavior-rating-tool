<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }

        // tami added for testing
        $user = $request->user();
        dd($user->hasRole('developer')); //will return true, if user has role
        dd($user->givePermissionsTo('create-tasks'));// will return permission, if not null
        dd($user->can('create-tasks')); // will return true, if user has permission

    }
}
