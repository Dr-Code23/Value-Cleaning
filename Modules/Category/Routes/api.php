<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Category\Http\Controllers\Admin\CategoryController;

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


Route::middleware(['user_api', 'setlocale', 'permission:category-list|category-create|category-edit|category-delete'])->prefix("admin")->group(function () {

    Route::get('Category', [CategoryController::class, 'index']);
    Route::post('create/Category', [CategoryController::class, 'store']);
    Route::get('show/Category/{id}', [CategoryController::class, 'show']);
    Route::post('update/Category/{id}', [CategoryController::class, 'update']);
    Route::delete('delete/Category/{id}', [CategoryController::class, 'destroy']);
});
