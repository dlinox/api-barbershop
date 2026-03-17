<?php

use Illuminate\Support\Facades\Route;
use App\Common\Http\Controllers\ServerTimeController;

Route::middleware('auth:api')->group(function () {
    Route::get('/', function () {
        return response()->json([
            'message' => 'API is running',
            'version' => '1.0.0'
        ]);
    });

    Route::get('/server-time', [ServerTimeController::class, 'getServerTime']);
});

// Module routes are now registered in AppServiceProvider
