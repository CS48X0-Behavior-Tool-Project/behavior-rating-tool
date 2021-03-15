<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PagesController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NewAccountController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\AccountController;

use App\Http\Controllers\CreateQuizController;

use App\Http\Controllers\Resources\VideoController;

use App\Http\Controllers\ExportController;

use App\Http\Controllers\QuizAttemptController;
use App\Http\Controllers\EditQuizController;
use App\Http\Controllers\SingleUserController;

Auth::routes();

Route::resource('users', UserController::class);
Route::resource('videos', VideoController::class);

/**
 * Login page is the landing page when we first visit the website
 */
Route::get('/', [PagesController::class, 'getLoginPage'])->name('login_page');

/**
 * Home page is the landing page when we first log in to the website
 */
Route::get('/home', [PagesController::class, 'getHomePage'])->name('home_route');

/**
 * Account creation/confirmation page
 */
Route::get('/confirmation', [PagesController::class, 'getConfirmationPage'])->name('confirmation_route');

/**
 * Add user page
 */
Route::get('/add_user', [PagesController::class, 'getAddUser'])->name('add_user_route');

/**
 * Create quiz page
 */
Route::get('/create_quiz', [PagesController::class, 'getCreateQuiz'])->name('create_quiz_route');

/**
 * Edit quiz page
 */
Route::get('/edit_quiz', [PagesController::class, 'getEditQuiz'])->name('edit_quiz_route');
Route::get('/edit_quiz/{id}', [PagesController::class, 'getEditQuizByID']);


/**
*  Submit the updated quiz with new changes.
*/
Route::post('/edit_quiz/{id}', [EditQuizController::class, 'updateQuiz']);

/**
 * Account management page (first/last names, email, password changes)
 */
Route::get('/account', [PagesController::class, 'getAccountManagement'])->name('account_route');

/**
 * List of possible quizzes to attempt
 */
Route::get('/quizzes', [PagesController::class, 'getQuizList'])->name('quizzes_route');

/**
 * Display the quiz we are attempting
 */
Route::get('/quizzes/{id}', [PagesController::class, 'getQuizById']);

/**
 * Show all the users in the system
 */
Route::get('/users', [PagesController::class, 'getUsers'])->name('users_route');;

/**
 * Display the user
 */
Route::get('/user/{id}', [PagesController::class, 'getUserById']);

/**
* Perform an action on the single user page
*/
Route::post('/user/{id}', [SingleUserController::class, 'action']);

/**
 * Route for submitting a login request.  Will need to test when actual webpage is created.
 */
Route::post('/', [LoginController::class, 'submit']);

/**
 * Called when the email link to a new user is clicked
 */
Route::get('/confirmation/{token}', [UploadController::class, 'validateToken']);

/**
 * Route for creating new users.
 */
Route::post('/add_user', [UploadController::class, 'upload']);

/**
 * Route for account management page.
 */
Route::post('/account', [AccountController::class, 'update']);

/**
 * Route for creating a new quiz.
 */
Route::post('/create_quiz', [CreateQuizController::class, 'createQuiz']);

/**
 * Route for confirming a new account.
 */
Route::post('/confirmation', [NewAccountController::class, 'createAccount']);

/*
* Route for submitting a quiz attempt
*/
Route::post('/quizzes/{id}', [QuizAttemptController::class, 'submitQuizAttempt']);

/**
 * Route for exporting data
 */
Route::get('/export', [PagesController::class, 'exportData']);
Route::get('/export/users', [ExportController::class, 'exportUsers'])->name('export_users_route');
Route::get('/export/user_quizzes', [ExportController::class, 'exportUserAttempts'])->name('export_user_quizzes_route');
