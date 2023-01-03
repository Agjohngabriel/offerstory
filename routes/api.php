<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('customer')->group(function () {
    Route::post('/register', [App\Http\Controllers\Api\v1\AuthController::class,'register'])->name('customer.register');
    Route::post('/login', [App\Http\Controllers\Api\v1\AuthController::class,'login'])->name('customer.login');
    Route::group([ 'middleware' =>  ['auth:api']], function() {
        Route::post('/update', [App\Http\Controllers\Api\v1\AuthController::class,'update'])->middleware('role:customer')->name('customer.update');
    });
    
});

Route::prefix('store')->group(function () {
    Route::post('/register', [App\Http\Controllers\Api\v1\AuthController::class,'store_register'])->name('store.register');
    Route::post('/login', [App\Http\Controllers\Api\v1\AuthController::class,'store_login'])->name('store.login');
    Route::group([ 'middleware' =>  ['auth:api']], function() {
        Route::post('/update', [App\Http\Controllers\Api\v1\AuthController::class,'update'])->middleware('role:store')->name('store.update');
    });
    
});

Route::get('/countries', [App\Http\Controllers\Api\v1\HomeController::class,'countries'])->name('countries');
Route::get('/countries/{id}', [App\Http\Controllers\Api\v1\HomeController::class,'countries_regions'])->name('countries.regions');
Route::get('/regions', [App\Http\Controllers\Api\v1\HomeController::class,'regions'])->name('regions');

