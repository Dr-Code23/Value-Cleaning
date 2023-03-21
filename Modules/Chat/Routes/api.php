<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Chat\Http\Controllers\ChatController;


Route::group(['middleware' => 'user_api'], function () {

    Route::post('checkroom', [ChatController::class, 'check']);
    Route::post('read/{id}', [ChatController::class, 'read']);
    Route::post('store', [ChatController::class, 'store']);
    Route::post('room', [ChatController::class, 'room']);
    Route::post('room-messages', [ChatController::class, 'message']);
    Route::post('delete/{id}', [ChatController::class, 'destroy']);

});
