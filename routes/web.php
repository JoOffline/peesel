<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('items', ItemController::class);
    Route::resource('transactions', TransactionController::class);
    Route::resource('carts', CartController::class);
    
    Route::get('checkout', [TransactionController::class, 'checkout'])->name('checkout');
    Route::post('process', [TransactionController::class, 'process'])->name('process');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
