<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UploadController;

Auth::routes();

Route::get('/', function () {
    return view('auth/login');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');



/**
* Routes for uploading a csv file to the web page.
*/
Route::get('/upload', [UploadController::class, 'getUploadPage']);

Route::post('/upload', [UploadController::class, 'uploadFile']);
