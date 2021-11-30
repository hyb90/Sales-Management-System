<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1'], function () {
Route::apiResource('sales',\App\Http\Controllers\SalesController::class);
Route::get('transactions/log',[\App\Http\Controllers\SalesController::class,'log']);
Route::apiResource('products',\App\Http\Controllers\ProductsController::class);
Route::apiResource('clients',\App\Http\Controllers\ClientsController::class);
Route::apiResource('categories',\App\Http\Controllers\CategoriesController::class);
Route::apiResource('sellers',\App\Http\Controllers\SellersController::class);
});
