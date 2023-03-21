<?php

use Illuminate\Http\Request;
use Modules\Chat\Http\Controllers\ChatController;





Route::group(['middleware' => 'auth:api'], function () {

Route::get('allroom', [ChatController::class, 'all']);
Route::post('checkroom', [ChatController::class, 'check']);
Route::post('read/{id}', [ChatController::class, 'read']);
Route::post('store', [ChatController::class, 'store']);
Route::post('room', [ChatController::class, 'room']);
Route::post('delete/{id}', [ChatController::class, 'destroy']);

});
