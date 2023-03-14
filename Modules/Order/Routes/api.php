<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Order\Http\Controllers\Admin\OrderAdminController;
use Modules\Order\Http\Controllers\User\OrderController;
use Modules\Order\Http\Controllers\User\StripeController;

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

Route::middleware('auth:api')->get('/order', function (Request $request) {
    return $request->user();
});


Route::middleware(['user_api','role:user'])->group(function() {
    Route::get('active/Order/{id}', [OrderController::class, 'activate']);
    Route::get('order', [OrderController::class, 'index']);
    Route::get('canceledOrder', [OrderController::class, 'canceledOrder']);
    Route::get('cancel/{id}', [OrderController::class, 'cancel']);
    Route::get('order_Code/{id}', [OrderController::class, 'orderCode']);
    Route::get('finishedOrder', [OrderController::class, 'finishedOrder']);
    Route::post('create/Order', [OrderController::class, 'store']);
    Route::post('update/Order/{id}', [OrderController::class, 'update']);
    Route::get('show/order/{id}', [OrderController::class, 'show']);
    Route::post('delete/Order/{id}', [OrderController::class, 'destroy']);
    Route::get('/order/pdf/{id}', [OrderController::class, 'createPdf']);
    Route::post('make-payment',[StripeController::class,'makePayment']);
    Route::get('all-payment',[StripeController::class,'allPayment']);
    Route::post('checkout-payment',[StripeController::class,'checkoutPayment']);
    Route::post('delete-payment',[StripeController::class,'deletePayment']);
});

Route::middleware(['user_api','role:admin'])->prefix("admin")->group(function() {
    Route::post('worker/update/Order/{id}', [OrderAdminController::class, 'updateOrderToAdmin']);
    Route::post('states/Order/{id}', [OrderAdminController::class, 'changeStates']);
    Route::get('order', [OrderAdminController::class, 'index']);
    Route::get('canceledOrder', [OrderAdminController::class, 'canceledOrder']);
    Route::get('finishedOrder', [OrderAdminController::class, 'finishedOrder']);
    Route::get('show/Order/{id}', [OrderAdminController::class, 'show']);
    Route::delete('delete/Order/{id}', [OrderAdminController::class, 'destroy']);
    Route::get('New-order-notification', [OrderAdminController::class, 'sendNewOrderNotification']);
    Route::get('home', [OrderAdminController::class, 'home']);
    Route::get('service-count', [OrderAdminController::class, 'serviceCount']);



});
