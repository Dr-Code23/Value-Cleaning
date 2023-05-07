<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\Api\Admin\AdminChangePasswordAController;
use Modules\Auth\Http\Controllers\Api\Admin\AdminController;
use Modules\Auth\Http\Controllers\Api\Admin\AdminProfileController;
use Modules\Auth\Http\Controllers\Api\Admin\FrontEnd\AboutController;
use Modules\Auth\Http\Controllers\Api\Admin\FrontEnd\ContactUsController;
use Modules\Auth\Http\Controllers\Api\Admin\FrontEnd\FooterController;
use Modules\Auth\Http\Controllers\Api\Admin\FrontEnd\TermsAndConditionsController;
use Modules\Auth\Http\Controllers\Api\Admin\PermissionController;
use Modules\Auth\Http\Controllers\Api\Admin\RoleController;
use Modules\Auth\Http\Controllers\Api\Admin\SendNotificationController;
use Modules\Auth\Http\Controllers\Api\Admin\UserController;
use Modules\Auth\Http\Controllers\Api\Auth\AuthController;
use Modules\Auth\Http\Controllers\Api\Auth\ChangePasswordController;
use Modules\Auth\Http\Controllers\Api\Auth\CompanyController;
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
Route::post('client-company-register', [AuthController::class, 'clientCompanyRegister']);
Route::post('change/password', [RestePasswordController::class, 'forgotPassword']);
Route::post('forgot/check-code', [RestePasswordController::class, 'checkCode']);
Route::post('reset/password', [RestePasswordController::class, 'reset']);
Route::get('/auth/{provider}', [AuthController::class, 'redirectToProvider']);
Route::post('/auth/{provider}/callback', [AuthController::class, 'handleProviderCallback']);

/**
 * user
 */

Route::middleware(['user_api'])->group(function () {

    Route::get('all-company', [CompanyController::class, 'allCompanies']);
    Route::get('show-company/{id}', [CompanyController::class, 'showCompany']);
    Route::get('logout', [UserProfileController::class, 'logout']);
    Route::get('profile', [UserProfileController::class, 'profile']);
    Route::post('update/profile', [UserProfileController::class, 'updateProfile']);
    Route::post('change-password', [ChangePasswordController::class, 'changePassword']);
    Route::delete('delete-account', [UserProfileController::class, 'deleteAccount']);
    Route::get('notification', [AuthController::class, 'notification']);
    Route::get('unreadNotification', [AuthController::class, 'unreadNotification']);
    Route::delete('deleteNotification/{id}', [AuthController::class, 'deleteNotification']);
    Route::get('about', [AboutController::class, 'index']);


});

/**
 * company
 */

Route::post('companyRegister', [CompanyController::class, 'companyRegister']);


/**
 * admin
 */
Route::post('Admin/Register', [AdminController::class, 'AdminRegister']);
Route::post('Admin/Login', [AdminController::class, 'AdminLogin']);

Route::middleware(['user_api'])->prefix("admin")->group(function () {
    Route::get('CompanyNotApproved', [CompanyController::class, 'allCompaniesNotApproved']);
    Route::get('approvedCompany/{id}', [CompanyController::class, 'approved']);
    Route::get('allCompany', [CompanyController::class, 'allCompanies']);
    Route::get('showCompany/{id}', [CompanyController::class, 'showCompany']);
    Route::get('show-user/{id}', [UserController::class, 'show']);
    Route::apiresource('roles', RoleController::class);
    Route::apiresource('users', UserController::class);
    Route::get('permission', [PermissionController::class, 'index']);
    Route::post('update-users/{id}', [UserController::class, 'update']);
    Route::delete('/delete-user/{id}', [UserController::class, 'destroy']);
    Route::get('all-users', [UserController::class, 'index']);
    Route::get('all-employee', [UserController::class, 'allEmployee']);
    Route::get('Admin/profile', [AdminProfileController::class, 'AdminProfile']);
    Route::post('update/profile', [AdminProfileController::class, 'AdminUpdateProfile']);
    Route::post('Admin-change-password', [AdminChangePasswordAController::class, 'adminChangePassword']);
    Route::get('logout', [AdminProfileController::class, 'Logout']);
    Route::post('send', [SendNotificationController::class, 'sendNotification']);
    Route::get('allNotification', [SendNotificationController::class, 'sendNotification']);
    Route::delete('deleteNotification/{id}', [SendNotificationController::class, 'sendNotification']);

    Route::get('all', [AdminController::class, 'all']);
    Route::apiresource('about', AboutController::class);
    Route::apiresource('footer', FooterController::class,);
    Route::apiresource('contact-us', ContactUsController::class,);
    Route::apiresource('terms-and-conditions', TermsAndConditionsController::class,);


});
/**
 *  FrontEnd --> Footer , ContactUs and  About
 */

Route::post('contact-us', [ContactUsController::class, 'store']);
Route::get('footer', [FooterController::class, 'index']);
Route::get('about', [AboutController::class, 'index']);


