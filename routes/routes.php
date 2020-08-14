<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth', 'middleware' => ['passport']], function () {

    Route::post('{provider}', [ \OZiTAG\Tager\Backend\Auth\Http\Controllers\AuthController::class, 'index']);

});
