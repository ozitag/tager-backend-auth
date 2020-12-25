<?php

use Illuminate\Support\Facades\Route;
use OZiTAG\Tager\Backend\Auth\Http\Controllers\AuthController;

Route::group(['prefix' => 'auth', 'middleware' => ['passport', 'throttle:5,1']], function () {

    Route::post('{provider}', [ AuthController::class, 'index']);

});
