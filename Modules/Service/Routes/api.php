<?php

use Modules\Service\Http\Controllers\Admin\ServiceController;
use Modules\Service\Http\Controllers\Admin\SubServiceController;
use Modules\Service\Http\Controllers\User\HomeController;


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



Route::middleware(['auth','role:admin'])->group(function(){

Route::get('service', [ServiceController::class, 'index']);
Route::post('create/service', [ServiceController::class, 'store']);
Route::get('show/service/{id}',  [ServiceController::class, 'show']);
Route::post('update/service/{id}',  [ServiceController::class, 'update']);
Route::post('delete/service/{id}', [ServiceController::class, 'destroy']);
Route::get('Active/service/{id}', [ServiceController::class, 'activate']);




Route::get('SubService', [SubServiceController::class, 'index']);
Route::post('create/SubService', [SubServiceController::class, 'store']);
Route::get('show/SubService/{id}',  [SubServiceController::class, 'show']);
Route::post('update/SubService/{id}',  [SubServiceController::class, 'update']);
Route::post('delete/SubService/{id}', [SubServiceController::class, 'destroy']);

});
Route::get('UserHome', [HomeController::class, 'UserHome']);
