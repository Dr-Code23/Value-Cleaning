<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\Api\Admin\AdminChangePasswordAController;
use Modules\Auth\Http\Controllers\Api\Admin\AdminController;
use Modules\Auth\Http\Controllers\Api\Admin\AdminProfileController;
use Modules\Auth\Http\Controllers\Api\Admin\RoleController;
use Modules\Auth\Http\Controllers\Api\Admin\SendNotificationController;
use Modules\Auth\Http\Controllers\Api\Admin\UserController;
use Modules\Auth\Http\Controllers\Api\Auth\AuthController;
use Modules\Auth\Http\Controllers\Api\Auth\ChangePasswordController;
use Modules\Auth\Http\Controllers\Api\Auth\RestePasswordController;
use Modules\Auth\Http\Controllers\Api\Auth\UserProfileController;

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
Route::get('/auth/{provider}', [AuthController::class,'redirectToProvider']);
Route::get('/auth/{provider}/callback', [AuthController::class, 'handleProviderCallback']);

Route::middleware(['user_api','role:user'])->group(function(){

    Route::get('logout', [UserProfileController::class, 'logout']);
    Route::get('profile', [UserProfileController::class, 'profile']);
    Route::post('update/profile', [UserProfileController::class, 'updateProfile']);
    Route::post('change-password', [ChangePasswordController::class, 'changePassword']);
    Route::delete('delete-account', [UserProfileController::class, 'deleteAccount']);


});

Route::post('Admin/Register', [AdminController::class, 'AdminRegister']);
Route::post('Admin/Login', [AdminController::class, 'AdminLogin']);

Route::middleware(['user_api','role:admin'])->prefix("admin")->group(function(){
    Route::apiresource('roles', RoleController::class);
    Route::get('all-users', [UserController::class,'index']);
    Route::get('Admin/profile', [AdminProfileController::class, 'AdminProfile']);
    Route::post('update/profile', [AdminProfileController::class, 'AdminUpdateProfile']);
    Route::post('Admin-change-password', [AdminChangePasswordAController::class, 'AdminchangePassword']);
    Route::get('logout', [AdminProfileController::class, 'Logout']);
    Route::post('send', [SendNotificationController::class, 'sendNotification']);

});
