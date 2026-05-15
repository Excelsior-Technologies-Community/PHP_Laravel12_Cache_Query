<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/', [ProductController::class, 'index']);

Route::post('/add-product', [ProductController::class, 'store']);

Route::delete('/delete-product/{id}', [ProductController::class, 'destroy']);