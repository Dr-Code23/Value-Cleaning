<?php

use Illuminate\Http\Request;
use Modules\Service\Http\Controllers\ServiceController;


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
});
