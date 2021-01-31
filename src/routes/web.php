<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NewAccountController;

Auth::routes();


/*
Route::get('/', function () {
    return view('auth/login');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
*/



/**
* Currently operating under the assumption that front end people are making blade templates for
* the approved designs, so I made these initial routes lead to simple HTML pages with still images
* of our designs.  These can be replaced later with the actual templates/views.
*/

/**
* Login page is the landing page when we first visit the website
*/
Route::get('/', [PagesController::class, 'getLoginPage']);

/**
* Home page is the landing page when we first log in to the website
*/
Route::get('/home', [PagesController::class, 'getHomePage']);

/**
* Account creation/confirmation page
*/
Route::get('/confirmation', [PagesController::class, 'getConfirmationPage']);

/**
* Add user page
*/
Route::get('/adduser', [PagesController::class, 'getAddUser']);

/**
* Account management page (first/last names, email, password changes)
*/
Route::get('/account', [PagesController::class, 'getAccountManagement']);

/**
* Route for submitting a login request.  Will need to test when actual webpage is created.
*/
Route::post('/submit', [LoginController::class, 'submit']);

/**
* Route for creating a single new user.  Will need to test when actual webpage is created.
*/
Route::post('/adduser/submit', [UserController::class, 'submit']);

/**
* Route for confirming a new account.  Will need to test when actual webpage is created.
*/
Route::post('/confirmation/submit', [NewAccountController::class, 'submit']);
