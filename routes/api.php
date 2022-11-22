<?php

use App\Http\Controllers\Api\admin\UserController as AdminController;
use App\Http\Controllers\Api\user\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::prefix('admin')->group(function () {
    Route::post('login', [AdminController::class, 'login']);
    Route::post('register', [AdminController::class, 'register']);

    Route::middleware('auth:api')->group(function () {
        Route::resource('posts', AdminController::class);
    });
});

Route::prefix('user')->group(function () {
    Route::post('login', [UserController::class, 'login']);
    // Route::post('register', [LoginController::class, 'register']);

    Route::middleware('auth:api')->group(function () {
        Route::get('index', [UserController::class,'index']);
    });
});
