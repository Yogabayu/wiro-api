<?php

use App\Http\Controllers\Api\admin\CategoriesController;
use App\Http\Controllers\Api\admin\CommentController;
use App\Http\Controllers\Api\admin\TourController;
use App\Http\Controllers\Api\admin\UserController as AdminController;
use App\Http\Controllers\Api\user\SearchController;
use App\Http\Controllers\Api\user\TourController as UserTourController;
use App\Http\Controllers\Api\user\UserCommentController;
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
        Route::post('logout',[AdminController::class,'logout']);

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
    Route::post('register', [UserController::class, 'register']);

    Route::middleware('auth:api')->group(function () {
        //acount
        Route::resource('account',UserController::class);
        Route::get('detail/{id}',[UserController::class,'detail']);
        Route::post('logout',[UserController::class,'logout']);

        //search
        Route::post('search',[SearchController::class,'search']);

        //index
        Route::resource('index',UserTourController::class);
        Route::get('tourdetail/{id}',[UserTourController::class,'detail']);

        //comment
        Route::resource('comment',UserCommentController::class);
    });
});
