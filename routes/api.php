<?php

use App\Http\Controllers\API\InfoRateController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SubscriptionController;

Route::get('/health', fn () => response()->json(['status' => 'ok']));
Route::get('/subscription/check', [SubscriptionController::class, 'check']);
Route::get('/license/validate', [SubscriptionController::class, 'license_check']);
Route::get('/ping', function () {
    return response()->json(['message' => 'pong']);
});

Route::get('/getrates', [InfoRateController::class, 'getRates'])->name('info-rate.api');
