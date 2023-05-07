<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\SubCategory\Http\Controllers\Admin\SubCategoryController;

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

    Route::get('subCategory', [SubCategoryController::class, 'index']);
    Route::post('create/subCategory', [SubCategoryController::class, 'store']);
    Route::get('show/subCategory/{id}', [SubCategoryController::class, 'show']);
    Route::post('update/subCategory/{id}', [SubCategoryController::class, 'update']);
    Route::delete('delete/subCategory/{id}', [SubCategoryController::class, 'destroy']);
});
