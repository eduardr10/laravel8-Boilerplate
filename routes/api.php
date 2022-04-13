<?php

use App\Http\Controllers\Api\AuthController;
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

Route::post('auth/login', [AuthController::class, 'login']);
Route::group(['middleware' => 'auth:api'], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('signup', [AuthController::class, 'signUp'])->middleware('can:create.user');
        Route::get('logout', [AuthController::class, 'logout']);
    });
    Route::group(['prefix' => 'user'], function () {
        Route::get('', [AuthController::class, 'user']);
        Route::patch('update/{user}', [AuthController::class, 'userUpdate'])->middleware('can:update.user');
    });
});
