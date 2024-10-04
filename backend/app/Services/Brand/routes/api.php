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

// Prefix: /api/brand

use App\Services\Brand\Http\Controllers\BrandController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'brand'], function() {

    // Controllers live in src/Services/Category/Http/Controllers

    Route::get('/', [
        BrandController::class,
        'index'
    ]);
    Route::get('edit/{id}', [
        BrandController::class,
        'getBrand'
    ]);
    Route::post('edit/{id}', [
        BrandController::class,
        'editBrand'
    ]);
    Route::post('delete/{id}', [
        BrandController::class,
        'deleteBrand'
    ]);
    Route::post('update-status',[
        BrandController::class,
        'updateStatusBrand'
    ]);
    Route::get('get-all-Brand-name', [
        BrandController::class,
        'getAllBrandName'
    ]);
    Route::post('create', [
        BrandController::class,
        'create'
    ]);
});
