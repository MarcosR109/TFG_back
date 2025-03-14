<?php

use App\Http\Controllers\CancioneController;
use App\Http\Controllers\LineaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AcordeController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(CancioneController::class)->group(function () {
    Route::get('canciones', 'index');
    Route::post('canciones', 'store');
});

Route::controller(LineaController::class)->group(function () {
    Route::post('lineas', 'store');
});

Route::controller(AcordeController::class)->group(function () {
    Route::get('acordes', 'index');
});
