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

Route::middleware('auth:api')->get('/category', function (Request $request) {
    return $request->user();
});


Route::middleware(['auth','role:admin'])->group(function() {

    Route::get('Category', [CategoryController::class, 'index']);
    Route::post('create/Category', [CategoryController::class, 'store']);
    Route::get('show/Category/{id}', [CategoryController::class, 'show']);
    Route::post('update/Category/{id}', [CategoryController::class, 'update']);
    Route::post('delete/Category/{id}', [CategoryController::class, 'destroy']);
});
