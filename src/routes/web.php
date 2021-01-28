<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

/*Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');*/

//login page is the landing page when we first visit the website
Route::get('/', function () {
    return view('pages.login');
});


//account creation/confirmation page
Route::get('/confirmation', function () {
    return view('pages.confirmation');
});

//add user page
Route::get('/adduser', function () {
    return view('pages.adduser');
});

//account management page (first/last names, email, password changes)
Route::get('/account', function () {
    return view('pages.account');
});
