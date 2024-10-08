<?php

/*
|--------------------------------------------------------------------------
| Service - API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for this service.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// Prefix: /api/supplier

use App\Services\Supplier\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'supplier'], function() {

    // Controllers live in src/Services/Category/Http/Controllers

    Route::get('/', [
        SupplierController::class,
        'index'
    ]);
    Route::get('edit/{id}', [
        SupplierController::class,
        'getSupplier'
    ]);
    Route::post('edit/{id}', [
        SupplierController::class,
        'editSupplier'
    ]);
    Route::post('delete/{id}', [
        SupplierController::class,
        'deleteSupplier'
    ]);
    Route::post('update-status',[
        SupplierController::class,
        'updateStatusSupplier'
    ]);
    Route::post('create', [
        SupplierController::class,
        'createSupplier'
    ]);
});
