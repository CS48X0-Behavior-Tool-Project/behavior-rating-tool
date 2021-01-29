<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

/*
Route::get('/', function () {
    return view('welcome');
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
Route::get('/', 'App\Http\Controllers\PagesController@getLoginPage');

/**
* Account creation/confirmation page
*/
Route::get('/confirmation', 'App\Http\Controllers\PagesController@getConfirmationPage');

/**
* Add user page
*/
Route::get('/adduser', 'App\Http\Controllers\PagesController@getAddUser');

/**
* Account management page (first/last names, email, password changes)
*/
Route::get('/account', 'App\Http\Controllers\PagesController@getAccountManagement');

/**
* Route for submitting a login request.
*/
Route::post('/login', 'App\Http\Controllers\LoginController@submit');
