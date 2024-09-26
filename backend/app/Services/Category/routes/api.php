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

// Prefix: /api/category

use App\Services\Category\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'category'], function() {

    // Controllers live in src/Services/Category/Http/Controllers

    Route::get('/', [
        CategoryController::class,
        'index'
    ]);

    Route::middleware('auth:api')->get('/user', function (Request $request) {
        return $request->user();
    });

});