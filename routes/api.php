<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;

Route::post('login', [ApiController::class, 'authenticate']);
Route::post('register', [ApiController::class, 'register']);

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('logout', [ApiController::class, 'logout']);
    Route::get('get_user', [ApiController::class, 'get_user']);
    Route::get('products', [ProductController::class, 'index']);
    Route::get('products/{id}', [ProductController::class, 'show']);
    Route::post('create', [ProductController::class, 'store']);
    Route::patch('update/{product}',  [ProductController::class, 'update']);
    Route::patch('increase/{product}',  [ProductController::class, 'increase']);
    Route::delete('delete/{product}',  [ProductController::class, 'destroy']);
    Route::get('categories', [CategoryController::class, 'index']);
    Route::post('createCategory', [CategoryController::class, 'store']);

});