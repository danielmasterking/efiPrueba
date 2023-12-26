<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::middleware('auth:api')->post('/logout', [AuthController::class, 'logout']);

Route::resource('products', ProductController::class);

Route::get('/listProduct', [ProductController::class, 'index']);

Route::middleware('auth:api')->group(function () {
    Route::post('/createProduct', [ProductController::class, 'store']);
    Route::get('/showProduct/{id}', [ProductController::class, 'show']);
    Route::put('/updateProduct/{id}', [ProductController::class, 'update']);
    Route::delete('/deleteProduct/{id}', [ProductController::class, 'destroy']);

    Route::get('/listCart', [CartController::class, 'index']);
    Route::post('/addCart', [CartController::class, 'create']);
});
