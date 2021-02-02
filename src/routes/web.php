<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PagesController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NewAccountController;
use App\Http\Controllers\UploadController;

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
Route::get('/add_user', [PagesController::class, 'getAddUser']);

/**
* Create quiz page
*/
Route::get('/create_quiz', [PagesController::class, 'getCreateQuiz']);

/**
* Account management page (first/last names, email, password changes)
*/
Route::get('/account', [PagesController::class, 'getAccountManagement']);

/**
* List of possible quizzes to attempt
*/
Route::get('/quizzes', [PagesController::class, 'getQuizList']);


/**
* Post routes will have to be tested later when we have database interaction.
*/

/**
* Route for submitting a login request.  Will need to test when actual webpage is created.
*/
Route::post('/', [LoginController::class, 'submit']);

/**
* Route for creating a single new user.  Will need to test when actual webpage is created.
*/
Route::post('/add_user/submit', [UserController::class, 'submit']);

/**
* Route for confirming a new account.  Will need to test when actual webpage is created.
*/
Route::post('/confirmation/submit', [NewAccountController::class, 'submit']);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');



/**
* Routes for uploading a csv file to the web page.
*/
Route::get('/upload', [UploadController::class, 'getUploadPage']);

Route::post('/upload', [UploadController::class, 'uploadFile']);
