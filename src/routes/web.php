<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PagesController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NewAccountController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\AccountController;

Auth::routes();

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
Route::get('/add_user', [PagesController::class, 'getAddUser'])->name('add_user_route');

/**
* Create quiz page
*/
Route::get('/create_quiz', [PagesController::class, 'getCreateQuiz']);

/**
* Account management page (first/last names, email, password changes)
*/
Route::get('/account', [PagesController::class, 'getAccountManagement'])->name('account_route');

/**
* List of possible quizzes to attempt
*/
Route::get('/quizzes', [PagesController::class, 'getQuizList']);


/**
* Email testing stuff.
*/
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Mail;

Route::get('/email', function() {

  Mail::to('email@email.com')->send(new WelcomeMail());
  return new WelcomeMail();
});


/**
* Route for creating new users.
*/
Route::post('/add_user', [UploadController::class, 'upload']);

/**
* Route for account management page.
*/
Route::post('/account', [AccountController::class, 'update']);
