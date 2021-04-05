<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PagesController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\NewAccountController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\AccountController;

use App\Http\Controllers\CreateQuizController;

use App\Http\Controllers\Resources\VideoController;

use App\Http\Controllers\ExportController;

use App\Http\Controllers\QuizAttemptController;
use App\Http\Controllers\EditQuizController;
use App\Http\Controllers\SingleUserController;

use App\Http\Controllers\JsonController;

Auth::routes();

// Resource controllers
Route::resource('videos', VideoController::class);

/**
 * Login page is the landing page when we first visit the website
 * ! prefix: login
 * ! name: login
 * * Example: route name is login.get.index, actual route is /
 */
Route::group(['as' => 'login'], function() {
    Route::get('/', [PagesController::class, 'getLoginPage'])->name('.get.index');

    Route::post('/', [LoginController::class, 'submit'])->name('.post.login');
});

/**
 * User related routes
 * ! prefix: user
 * ! name: user
 * * Example: route name is user.get.user, actual route is /user/{id}
 */
Route::group(['prefix' => 'user', 'as' => 'user'], function() {
    Route::get('/add', [PagesController::class, 'getAddUser'])->name('.get.add');
    Route::get('/list', [PagesController::class, 'getUsers'])->name('.get.index');
    Route::get('/get/{id}', [PagesController::class, 'getUserById'])->name('.get.user');

    Route::post('/action/{id}', [SingleUserController::class, 'action'])->name('.post.action');
    Route::post('/add', [UploadController::class, 'upload'])->name('.post.upload');
    Route::post('/template', [JsonController::class, 'downloadJsonTemplate'])->name('.post.template');
});

/**
 * Account related routes
 * ! prefix: account
 * ! name: account
 * * Example: route name is account.get.index, actual route is /account
 */
Route::group(['prefix' => 'account', 'as' => 'account'], function() {
    Route::get('/', [PagesController::class, 'getAccountManagement'])->name('.get.index');

    Route::post('/', [AccountController::class, 'update'])->name('.post.index');
});

/**
 * Routes for everything quiz related
 * ! prefix: quizzes
 * ! name: quizzes
 * * Example: route name is quiz.get.edit, actual route is /quiz/edit
 */
Route::group(['prefix' => 'quiz', 'as' => 'quiz'], function() {
    Route::get('/list', [PagesController::class, 'getQuizList'])->name('.get.index');
    Route::get('/display/{id}', [PagesController::class, 'getQuizById'])->name('.get.quiz');
    Route::get('/edit', [PagesController::class, 'getEditQuiz'])->name('.get.edit');
    Route::get('/edit/{id}', [PagesController::class, 'getEditQuizByID'])->name('.get.edit.id');
    Route::get('/create', [PagesController::class, 'getCreateQuiz'])->name('.get.create');

    Route::post('/edit/{id}', [EditQuizController::class, 'updateQuiz'])->name('.post.update');
    Route::post('/create', [CreateQuizController::class, 'createQuiz'])->name('.post.create');
    Route::post('/submit/{id}', [QuizAttemptController::class, 'submitQuizAttempt'])->name('.post.submit');
});

/**
 * Routes for account confirmation
 * ! prefix: confirmation
 * ! name: confirmation
 * * Example: route name is confirmation.get.token, actual route is /confirmation/{token}
 */
Route::group(['prefix' => 'confirmation', 'as' => 'confirmation'], function() {
    Route::get('/{token}', [UploadController::class, 'validateToken'])->name('.get.token');

    Route::post('/', [NewAccountController::class, 'createAccount'])->name('.post.create');
});

/**
 * Routes for exporting data
 * ! prefix: export
 * ! name: export
 * * Example: route name is export.get.index, actual route is /export
 */
Route::group(['prefix' => 'export', 'as' => 'export'], function() {
    Route::get('/', [PagesController::class, 'exportData'])->name('.get.index');
    Route::get('/users', [ExportController::class, 'exportUsers'])->name('.get.users');
    Route::get('/quizzes', [ExportController::class, 'exportUserAttempts'])->name('.get.quizzes');
});
