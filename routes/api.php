<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('categories', CategoryController::class);
Route::apiResource('products', ProductController::class);
/*
Route::get('/categories', [CategoryController::class, 'getList']);
Route::post('/categories', [CategoryController::class, 'create']);
//Route::get('/categories', [CategoryController::class, 'get']);
Route::delete('/categories', [CategoryController::class, 'delete']);*/
