<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BidController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::controller(ProductController::class)->group(function () {
        Route::post('/product', 'store')->name('products.store');
        Route::delete('/products/{product}', 'destroy')->name('products.destroy');
        Route::get('/products/{product}', 'show')->name('products.show');
    });

     Route::controller(BidController::class)->group(function () {
        Route::get('/bid', 'index')->name('bid.index');
    });

    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

});

require __DIR__.'/auth.php';
