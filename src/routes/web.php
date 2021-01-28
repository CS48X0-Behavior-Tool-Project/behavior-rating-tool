<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

/*Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');*/


/**
* Currently operating under the assumption that front end people are making blade templates for
* the approved designs, so I made these initial routes lead to simple HTML pages with still images
* of our designs.  These can be replaced later with the actual templates/views.
*/

/**
* Login page is the landing page when we first visit the website
*/
Route::get('/', function () {
    return view('pages.login');
});


/**
* Account creation/confirmation page
*/
Route::get('/confirmation', function () {
    return view('pages.confirmation');
});

/**
* Add user page
*/
Route::get('/adduser', function () {
    return view('pages.adduser');
});

/**
* Account management page (first/last names, email, password changes)
*/
Route::get('/account', function () {
    return view('pages.account');
});
