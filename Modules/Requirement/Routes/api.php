<?php

use Illuminate\Support\Facades\Route;
use Modules\Requirement\Http\Controllers\Admin\RequirementController;

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


Route::middleware(['user_api', 'setlocale'])->prefix("admin")->group(function () {
    Route::get('requirement', [RequirementController::class, 'index']);
    Route::post('create/requirement', [RequirementController::class, 'store']);
    Route::get('show/requirement/{id}', [RequirementController::class, 'show']);
    Route::post('update/requirement/{id}', [RequirementController::class, 'update']);
    Route::delete('delete/requirement/{id}', [RequirementController::class, 'destroy']);
});
