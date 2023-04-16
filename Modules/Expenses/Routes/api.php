<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Expenses\Http\Controllers\ExpenseController;
use Modules\Expenses\Http\Controllers\PaymentController;
use Modules\Expenses\Http\Controllers\TypeController;


Route::group(['middleware' => 'user_api'], function () {
    Route::post('store-expense', [ExpenseController::class, 'store']);
    Route::get('get-expense', [ExpenseController::class, 'index']);
    Route::post('update-expense/{id}', [ExpenseController::class, 'update']);
    Route::delete('delete-expense/{id}', [ExpenseController::class, 'destroy']);
    Route::post('edit-expense', [ExpenseController::class, 'edit']);
    Route::get('search', [ExpenseController::class, 'search']);

    Route::post('store-type', [TypeController::class, 'store']);
    Route::get('get-type', [TypeController::class, 'index']);
    Route::post('update-type/{id}', [TypeController::class, 'update']);
    Route::delete('delete-type/{id}', [TypeController::class, 'destroy']);
    Route::post('edit-type', [TypeController::class, 'edit']);

    Route::post('store-payment', [PaymentController::class, 'store']);
    Route::get('get-payment', [PaymentController::class, 'index']);
    Route::post('update-payment/{id}', [PaymentController::class, 'update']);
    Route::delete('delete-payments/{id}', [PaymentController::class, 'destroy']);
    Route::post('edit-payment', [PaymentController::class, 'edit']);
});
