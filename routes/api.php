<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\AuthController;


Route::post('register',[AuthController::class,"register"]);
Route::post('login',[AuthController::class,"login"]);

Route::middleware('auth:sanctum')->group(function () {

    Route::controller(AuthController::class)->group(function () {
        Route::get('user',"user");
        Route::post('logout',"logout");
    });

    Route::controller(ProductController::class)->group(function () {
        Route::get('/products', 'index');
        Route::get('/products/{id}', 'show');
        Route::post('/products',"store")->name("add_product");
        Route::put('/products/{id}', 'update');
        Route::delete('/products/{id}', 'delete');
    });
    
    Route::controller(OrderController::class)->group(function () {
        Route::get('/orders', 'index');
        Route::get('/orders/{id}', 'show');
        Route::post('/orders', 'store');
        Route::put('/orders/{id}', 'update');
        Route::delete('/orders/{id}', 'delete');
    });
});

//Route::get('users',[AuthController::class,"index"]);