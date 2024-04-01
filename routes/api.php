<?php

use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Admin\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');

});

Route::controller(CategoryController::class)->group(function(){
    Route::get('/category', 'index');
    Route::post('/store/category','store');
    Route::post('/update/category','update');
    Route::post('/delete/category','delete');
});

Route::controller(PostController::class)->group(function(){
    Route::get('post', 'index');
    Route::post('/store/post','store');
    Route::post('/update/post','update');
    Route::post('/delete/post','delete');
});
