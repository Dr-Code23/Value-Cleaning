<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Modules\Order\Http\Controllers\User\OrderController;

Route::prefix('order')->group(function() {
    Route::get('/', 'OrderController@index');
});



Route::get('/show/order', [OrderController::class, 'showEmployees']);
