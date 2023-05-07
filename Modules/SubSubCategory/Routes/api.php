<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\SubSubCategory\Http\Controllers\Admin\SubSubCategoryController;

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

    Route::get('sub-sub-category', [SubSubCategoryController::class, 'index']);
    Route::post('create/sub-sub-category', [SubSubCategoryController::class, 'store']);
    Route::get('show/sub-sub-category/{id}', [SubSubCategoryController::class, 'show']);
    Route::post('update/sub-sub-category/{id}', [SubSubCategoryController::class, 'update']);
    Route::delete('delete/sub-sub-category/{id}', [SubSubCategoryController::class, 'destroy']);
});
