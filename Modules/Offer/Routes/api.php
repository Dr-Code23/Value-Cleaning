<?php

use Illuminate\Http\Request;
use Modules\Offer\Http\Controllers\Admin\OfferController;

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

Route::middleware('auth:api')->get('/offer', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth','role:admin'])->group(function(){

    Route::get('Offer', [OfferController::class, 'index']);
    Route::post('create/Offer', [OfferController::class, 'store']);
    Route::get('show/Offer/{id}',  [OfferController::class, 'show']);
    Route::post('update/Offer/{id}',  [OfferController::class, 'update']);
    Route::post('delete/Offer/{id}', [OfferController::class, 'destroy']);
});
