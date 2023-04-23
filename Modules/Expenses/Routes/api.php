<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Expenses\Http\Controllers\ExpensesController;
use Modules\Expenses\Http\Controllers\PaymentController;
use Modules\Expenses\Http\Controllers\TypeController;


Route::group(['middleware' => 'user_api'], function () {
    Route::post('store-expense', [ExpensesController::class, 'store']);
    Route::get('get-expense', [ExpensesController::class, 'index']);
    Route::post('update-expense/{id}', [ExpensesController::class, 'update']);
    Route::delete('delete-expense/{id}', [ExpensesController::class, 'destroy']);
    Route::post('edit-expense', [ExpensesController::class, 'edit']);
    Route::get('search', [ExpensesController::class, 'search']);

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
