<?php

use Illuminate\Support\Facades\Route;
use OZiTAG\Tager\Backend\Auth\Http\Controllers\AuthController;

Route::group(['prefix' => 'auth', 'middleware' => ['throttle:5,1']], function () {
    Route::post('client', [ AuthController::class, 'client']);

    Route::group(['middleware' => ['passport']], function () {
        Route::post('{provider}/google', [ AuthController::class, 'google']);
        Route::post('{provider}', [ AuthController::class, 'index']);
    });
});
