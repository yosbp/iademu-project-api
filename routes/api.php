<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\WordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('providers', ProviderController::class);
    Route::apiResource('orders', OrderController::class);
    Route::get('payment-order/{order}', [ExcelController::class, 'createPaymentOrder']);
    Route::get('buy-order/{order}', [ExcelController::class, 'createBuyOrder']);
    Route::get('dashboard', [DashboardController::class, 'dashboard']);
});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
route::get('get-word', [WordController::class, 'createGoodsAndServicesWord']);
