<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StoreController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [StoreController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/stores', [StoreController::class, 'allStores'])->middleware(['auth', 'verified'])->name('stores');
Route::get('/approve/{id}', [StoreController::class, 'approve'])->middleware(['auth', 'verified'])->name('approve');
Route::get('/disapprove/{id}', [StoreController::class, 'disapprove'])->middleware(['auth', 'verified'])->name('disapprove');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
