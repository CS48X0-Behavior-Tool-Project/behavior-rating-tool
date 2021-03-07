<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\QuizController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\VideoController;
use App\Models\User;
use App\Models\Quiz;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| API Under Development
|--------------------------------------------------------------------------
|
| The API below is simply a rough scaffolding of what our API will look like
| as it currently is non functional (needs corresponding controllers) and
| and functions. Take care that these are created before calling them.
| These functions are currently visible to everyone, and eventually they
| should have properly implemented authorization using Bouncer. Please
| organize all associated API under the respective header comment.
|
*/

/**
 * Quiz Related API
 */

Route::get('quizzes', [QuizController::class, 'getAllQuizzes']);
Route::get('quizzes/{id}', [QuizController::class, 'getQuiz']);
Route::get('quizzes/{id}/attempts', [QuizController::class, 'getQuizAttempts']);
Route::post('quizzes/create', [QuizController::class, 'createQuiz']);
Route::put('quizzes/{id}', [QuizController::class, 'updateQuiz']);
Route::delete('quizzes/{id}', [QuizController::class, 'deleteQuiz']);

/**
 * User Related API
 */

Route::get('users', [UserController::class, 'getAllUsers']);
Route::get('users/{id}', [UserController::class, 'getUser']);
Route::get('users/{id}/attempts', [UserController::class, 'getUserAttempts']);
Route::post('users/{id}/attempts', [UserController::class, 'upsertUserAttempts']);      // update and insert => upsert
Route::delete('users/{id}/attempts', [UserController::class, 'deleteUserAllAttempts']);
Route::post('users/create', [UserController::class, 'createUser']);
Route::put('users/{id}', [UserController::class, 'updateUser']);
Route::delete('users/{id}', [UserController::class, 'deleteUser']);

/**
 * Video Related API
 */

Route::get('videos', [VideoController::class, 'getAllVideos']);
Route::get('videos/{id}', [VideoController::class, 'getVideo']);
Route::post('videos/upload', [VideoController::class, 'uploadVideo']);
Route::delete('videos/{id}', [VideoController::class, 'deleteVideo']);
