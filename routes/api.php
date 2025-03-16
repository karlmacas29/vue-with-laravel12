<?php

use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
//submit data
Route::post('/products', [ProductController::class, 'store']);
//get Data
Route::get('/products', [ProductController::class, 'index']);
//edit data
Route::get('/products/{products}/edit', [ProductController::class, 'edit']);
//update
Route::put('/products/{products}', [ProductController::class, 'update']);
//delete
Route::delete('/products/{products}', [ProductController::class, 'destroy']);