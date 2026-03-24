<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

// Show products (with cache)
Route::get('/', [ProductController::class, 'index']);

// Add product
Route::post('/add-product', [ProductController::class, 'store']);
