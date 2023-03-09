<?php

use Illuminate\Http\Request;
use Modules\Review\Http\Controllers\ReviewController;
use Modules\Review\Http\Controllers\WorkerReviewController;

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

Route::middleware(['user_api','role:user'])->group(function(){
    Route::get('Review', [ReviewController::class, 'index']);
    Route::post('create/Review', [ReviewController::class, 'store']);
    Route::post('update/Review/{id}', [ReviewController::class, 'update']);
    Route::get('show/Review/{id}', [ReviewController::class, 'show']);
    Route::delete('delete/Review/{id}', [ReviewController::class, 'destroy']);

    Route::get('Worker/Review', [WorkerReviewController::class, 'index']);
    Route::post('create/Worker/Review', [WorkerReviewController::class, 'store']);
    Route::post('update/Worker/Review/{id}', [WorkerReviewController::class, 'update']);
    Route::get('show/Worker/Review/{id}', [WorkerReviewController::class, 'show']);
    Route::delete('delete/Worker/Review/{id}', [WorkerReviewController::class, 'destroy']);


});
