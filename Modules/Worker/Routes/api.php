<?php

use Illuminate\Http\Request;
use Modules\Worker\Http\Controllers\WorkerController;

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

Route::middleware('auth:api')->get('/worker', function (Request $request) {
    return $request->user();
});



Route::middleware(['auth','role:admin'])->group(function(){
    Route::get('Active/worker/{id}', [WorkerController::class, 'activate']);
    Route::get('worker', [WorkerController::class, 'index']);
    Route::post('create/worker', [WorkerController::class, 'store']);
    Route::post('update/worker/{id}', [WorkerController::class, 'update']);
    Route::get('show/worker/{id}', [WorkerController::class, 'show']);
    Route::post('delete/worker/{id}', [WorkerController::class, 'destroy']);


});
