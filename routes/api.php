<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {
    Route::get('/', function () {
        return response()->json([
            'message' => 'API is running',
            'version' => '1.0.0'
        ]);
    });
});

// Module routes are now registered in AppServiceProvider
