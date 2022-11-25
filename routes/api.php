<?php

use App\Http\Controllers\Api\admin\CategoriesController;
use App\Http\Controllers\Api\admin\CommentController;
use App\Http\Controllers\Api\admin\DashboardController;
use App\Http\Controllers\Api\admin\EventController;
use App\Http\Controllers\Api\admin\TourController;
use App\Http\Controllers\Api\admin\UserController as AdminController;
use App\Http\Controllers\Api\GeneralControlller;
use App\Http\Controllers\Api\user\EventController as UserEventController;
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
//tour
Route::resource('general',GeneralControlller::class);
Route::get('detail/{id}',[GeneralControlller::class,'detailtour']);
//event
Route::get('event',[GeneralControlller::class,'event']);
Route::get('eventdetail/{id}',[GeneralControlller::class,'detailevent']);


Route::prefix('admin')->group(function () {
    Route::post('login', [AdminController::class, 'login']);
    
    Route::middleware('auth:api')->group(function () {
        //register
        Route::post('register', [AdminController::class, 'register']);

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
        //dashboard
        Route::resource('dashboard',DashboardController::class);

        //event
        Route::resource('event',EventController::class);
        Route::post('addcom',[EventController::class,'addcomment']);
        Route::put('updatecom/{id}',[EventController::class,'updatecomment']);
        Route::delete('deletecom/{id}',[EventController::class,'deletecomment']);
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

        //event
        Route::resource('event', UserEventController::class);
        Route::get('eventdet/{id}',[UserEventController::class,'detailevent']);
    });
});
