<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StockController;
use App\Http\Middleware\AutotraderAuthentication;
use App\Http\Controllers\WebhookController;

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

Route::prefix('v1')->group(function () {
    Route::middleware([AutotraderAuthentication::class])->group(function () {
        Route::get('stocks', [StockController::class, 'index']);
    });

    Route::get('stocks-data', [StockController::class, 'show']);
    Route::get('stock-detail/{stockId}', [StockController::class, 'findStockDetail']);
    Route::put('webhook', [WebhookController::class, 'update']);
});
