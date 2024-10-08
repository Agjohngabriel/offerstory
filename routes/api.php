<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\PasswordResetLinkController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/send-reset-password', [\App\Http\Controllers\ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::post('/verify-reset-password', [\App\Http\Controllers\ForgotPasswordController::class, 'reset']);
Route::post('/login', [App\Http\Controllers\Api\v1\AuthController::class,'login'])->name('login');
Route::post('/forget', [App\Http\Controllers\Api\v1\AuthController::class,'forget'])->name('forget');
Route::get('/store/{id}/stories', [App\Http\Controllers\Api\v1\HomeController::class,'get_story']);
Route::post('/follow/{id}', [App\Http\Controllers\Api\v1\UserActionController::class,'follow'])->middleware('auth:api');
Route::get('/store/auth/{id}', [App\Http\Controllers\Api\v1\HomeController::class,'get_store'])->name('search');
Route::group([ 'middleware' =>  ['auth:api']], function() {
    Route::post('/update-password', [\App\Http\Controllers\ForgotPasswordController::class, 'changePassword']);
    Route::post('/update/user', [App\Http\Controllers\Api\v1\AuthController::class,'update'])->name('user.update');
    Route::post('/logout', [App\Http\Controllers\Api\v1\AuthController::class,'logout'])->name('user.logout');
});
Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.reset');


Route::prefix('customer')->group(function () {
    Route::post('/signup', [App\Http\Controllers\Api\v1\AuthController::class,'register'])->name('customer.signup');
    Route::post('/resend/otp', [App\Http\Controllers\Api\v1\AuthController::class,'resend'])->name('customer.resend');
    Route::post('/verify/otp', [App\Http\Controllers\Api\v1\AuthController::class,'verify'])->name('customer.verify');
    Route::get('store/{id}/stories', [App\Http\Controllers\Api\v1\HomeController::class,'get_story'])->name('customer.get_story');
});

Route::prefix('store')->group(function () {
    Route::post('/signup', [App\Http\Controllers\Api\v1\AuthController::class,'store_register'])->name('store.signup');
    Route::post('/resend/otp', [App\Http\Controllers\Api\v1\AuthController::class,'resend'])->name('store.resend');
    Route::post('/verify/otp', [App\Http\Controllers\Api\v1\AuthController::class,'verify'])->name('store.verify');
    Route::group([ 'middleware' =>  ['auth:api']], function() {
        Route::post('/update/page', [App\Http\Controllers\Api\v1\AuthController::class,'update_store'])->middleware('role:store')->name('store.update.page');
        Route::post('/create/story', [App\Http\Controllers\Api\v1\StoreActionController::class,'story'])->middleware('role:store')->name('store.story');
        Route::post('/upload/media', [App\Http\Controllers\Api\v1\StoreActionController::class,'upload'])->middleware('role:store')->name('store.upload');
        Route::post('/branch', [App\Http\Controllers\Api\v1\StoreActionController::class,'branch'])->middleware('role:store')->name('store.branch');
        Route::get('/get', [App\Http\Controllers\Api\v1\StoreActionController::class,'get_store'])->middleware('role:store')->name('store.get_store');
        Route::delete('/branch/{id}', [App\Http\Controllers\Api\v1\StoreActionController::class,'delete_branch'])->middleware('role:store')->name('delete.branch');
        Route::delete('/story/{id}', [App\Http\Controllers\Api\v1\StoreActionController::class,'delete_story'])->middleware('role:store')->name('delete.story');

    });

});

Route::get('/countries', [App\Http\Controllers\Api\v1\HomeController::class,'countries'])->name('countries');
Route::get('/countries/{id}', [App\Http\Controllers\Api\v1\HomeController::class,'countries_regions'])->name('countries.regions');
Route::get('/regions', [App\Http\Controllers\Api\v1\HomeController::class,'regions'])->name('regions');
Route::get('/categories', [App\Http\Controllers\Api\v1\HomeController::class,'categories'])->name('categories');
Route::get('/home', [App\Http\Controllers\Api\v1\HomeController::class,'home'])->name('home');
Route::post('/stores/category/{id}', [App\Http\Controllers\Api\v1\HomeController::class,'stores'])->name('stores');
Route::post('/stores/category/auth/{id}', [App\Http\Controllers\Api\v1\HomeController::class,'stores'])->middleware('auth:api')->name('stores');
Route::get('/search', [App\Http\Controllers\Api\v1\HomeController::class,'search'])->name('search');
Route::get('/store/view/{id}', [App\Http\Controllers\Api\v1\HomeController::class,'get_store'])->name('search');
Route::get('/store/view/auth/{id}', [App\Http\Controllers\Api\v1\HomeController::class,'get_store'])->middleware('auth:api')->name('viewauth');
Route::get('/home/auth', [App\Http\Controllers\Api\v1\HomeController::class,'home'])->middleware('auth:api')->name('customer.home');
Route::get('/store/{id}/stories/auth', [App\Http\Controllers\Api\v1\HomeController::class,'get_story'])->middleware('auth:api');
