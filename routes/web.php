<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('categories', CategoryController::class);
    Route::resource('items', ItemController::class);
    Route::resource('transactions', TransactionController::class);
    
    // Tambahkan route untuk edit dan update status
    Route::get('/transactions/{transaction}/edit-status', [TransactionController::class, 'editStatus'])->name('transactions.edit-status');
    Route::patch('/transactions/{transaction}/update-status', [TransactionController::class, 'updateStatus'])->name('transactions.update-status');
    
    Route::get('checkout', [TransactionController::class, 'checkout'])->name('checkout');
    Route::post('process', [TransactionController::class, 'process'])->name('process');
    
    // Cart routes
    Route::get('/cart', [CartController::class, 'index'])->name('carts.index');
    Route::post('/carts', [CartController::class, 'store'])->name('carts.store');
    Route::patch('/carts/{cart}', [CartController::class, 'update'])->name('carts.update');
    Route::delete('/carts/{cart}', [App\Http\Controllers\CartController::class, 'destroy'])->name('carts.destroy');
    Route::post('/carts/checkout', [CartController::class, 'checkout'])->name('carts.checkout');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

