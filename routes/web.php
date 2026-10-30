<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::resource('products', ProductController::class);
Route::resource('categories', ProductCategoryController::class);
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');
