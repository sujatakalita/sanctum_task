<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\UserController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/add-user', [AuthenticationController::class, 'addUser']);
//login user
Route::post('/signin', [AuthenticationController::class, 'signin']);
//using middleware


Route::get('/unauthorized', [AuthenticationController::class,'logIn'])->name('unauthorized');
Route::get('/get-users', [UserController::class,'allUser'])->name('alluser');
Route::group(['middleware' => ['auth:sanctum'],'prefix'=>'user'], function () {
    Route::post('/update', [AuthenticationController::class, 'updateUser']);
    Route::get('/delete/{id}', [AuthenticationController::class, 'destroy']);
    Route::post('/sign-out', [AuthenticationController::class, 'logout']);
});
