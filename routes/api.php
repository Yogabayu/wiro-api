<?php

use App\Http\Controllers\Api\admin\CategoriesController;
use App\Http\Controllers\Api\admin\CommentController;
use App\Http\Controllers\Api\admin\TourController;
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
        //user
        Route::get('showadmin/{id}',[AdminController::class,'showAdmin']);
        Route::put('updateAdmin/{id}',[AdminController::class,'update']);
        Route::delete('delete/{id}',[AdminController::class,'destroy']);

        //categories
        Route::resource('categories', CategoriesController::class);
        //Tour
        Route::resource('tour', TourController::class);
        //Comment
        Route::resource('comment',CommentController::class);
    });
});

Route::prefix('user')->group(function () {
    Route::post('login', [UserController::class, 'login']);

    Route::middleware('auth:api')->group(function () {
        Route::get('index', [UserController::class,'index']);
    });
});
