<?php

use Illuminate\Http\Request;
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




Route::middleware(['user_api','role:admin'])->prefix("admin")->group(function() {

    Route::get('Category', [CategoryController::class, 'index']);
    Route::post('create/Category', [CategoryController::class, 'store']);
    Route::get('show/Category/{id}', [CategoryController::class, 'show']);
    Route::put('update/Category/{id}', [CategoryController::class, 'update']);
    Route::delete('delete/Category/{id}', [CategoryController::class, 'destroy']);
});
