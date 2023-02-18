<?php

use App\Http\Controllers\Api\Admin\RoleController;
use App\Http\Controllers\Api\Admin\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\RestePasswordController;

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




Route::post('login', [AuthController::class, 'Login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('change/password',  [RestePasswordController::class, 'forgotPassword']);
Route::post('forgot/check-code', [RestePasswordController::class, 'checkCode']);
Route::post('reset/password', [RestePasswordController::class, 'reset']);

Route::group(['middleware' => 'auth:api'], function () {

    Route::get('logout', [AuthController::class, 'Logout']);


});


Route::prefix("admin")->middleware(['auth','role:admin'])->group(function(){
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
});


Route::group(['middleware' => 'auth:api'], function () {
});
