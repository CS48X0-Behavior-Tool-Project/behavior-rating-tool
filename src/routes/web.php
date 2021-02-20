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

Route::resource('users', UserController::class);

/**
* Login page is the landing page when we first visit the website
*/
Route::get('/', [PagesController::class, 'getLoginPage']);

/**
* Home page is the landing page when we first log in to the website
*/
Route::get('/home', [PagesController::class, 'getHomePage'])->name('home_route');

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
* Post routes will have to be tested later when we have database interaction.
*/

/**
* Route for confirming a new account.
*/
<<<<<<< HEAD
Route::post('/', [LoginController::class, 'submit']);

/**
* Route for creating new users.
*/
Route::post('/add_user', [UploadController::class, 'upload']);

/**
* Route for account management page.
*/
Route::post('/account', [AccountController::class, 'update']);

/**
* Route for confirming a new account.  Will need to test when actual webpage is created.
*/
Route::post('/confirmation/submit', [NewAccountController::class, 'submit']);
=======
Route::post('/confirmation', [NewAccountController::class, 'createAccount']);
>>>>>>> 42dcee5755af290a41334c563d870cc67495fb6a
