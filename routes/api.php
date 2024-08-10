<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryProductsController;
use App\Http\Controllers\ProductsController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('v1')->group(function() {
    Route::post('auth/login', [AuthController::class, 'login']);
    Route::post('auth/register', [AuthController::class, 'register']);
    Route::get('category-products', [CategoryProductsController::class, 'index'])->middleware('auth:api');
    Route::get('category-products/{id}', [CategoryProductsController::class, 'show'])->middleware('auth:api');
    Route::post('category-products', [CategoryProductsController::class, 'store'])->middleware('auth:api');
    Route::put('category-products', [CategoryProductsController::class, 'update'])->middleware('auth:api');
    Route::delete('category-products/{id}', [CategoryProductsController::class, 'destroy'])->middleware('auth:api');
    Route::get('products', [ProductsController::class, 'index'])->middleware('auth:api');
    Route::get('products/{id}', [ProductsController::class, 'show'])->middleware('auth:api');
    Route::post('products', [ProductsController::class, 'store'])->middleware('auth:api');
    Route::post('products/{id}', [ProductsController::class, 'update'])->middleware('auth:api');
    Route::delete('products/{id}', [ProductsController::class, 'destroy'])->middleware('auth:api');
});

Route::get('/', function () {
    return response()->json([
        "message" => "Endpoint yang anda minta tidak tersedia"
    ], 404);
});

Route::fallback(function () {
    return response()->json([
        "message" => "Endpoint yang anda minta tidak tersedia"
    ], 404);
});
