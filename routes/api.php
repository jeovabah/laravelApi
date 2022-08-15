<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
#Public Routes
// Route::resource('products', ProductController::class);
Route::post("register", [AuthController::class, "register"]);
Route::post('login', [AuthController::class, 'login']);

Route::get('products', [ProductController::class, 'index']);
Route::get('products/search/{name}', [ProductController::class, 'search']);

//Protected Routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('products', [ProductController::class, 'store']);
    Route::put('products/{product}', [ProductController::class, 'update']);
    Route::delete('products/{product}', [ProductController::class, 'destroy']);
});



