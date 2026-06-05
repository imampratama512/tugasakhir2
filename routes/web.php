<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::get('/dashboard', function () {
    $totalCategories = \App\Models\Category::count();
    $totalSuppliers = \App\Models\Supplier::count();
    $totalItems = \App\Models\Item::count();
    $lowStockItems = \App\Models\Item::whereRaw('current_stock <= minimum_stock')->get();
    $recentTransactions = \App\Models\Transaction::with('item')->latest('transaction_date')->take(5)->get();

    return view('dashboard', compact('totalCategories', 'totalSuppliers', 'totalItems', 'lowStockItems', 'recentTransactions'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('suppliers', SupplierController::class)->except(['show']);
    Route::resource('items', ItemController::class)->except(['show']);
    Route::resource('transactions', TransactionController::class);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
