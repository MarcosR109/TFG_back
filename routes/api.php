<?php

use App\Http\Controllers\CancioneController;
use App\Http\Controllers\LineaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AcordeController;
use App\Http\Controllers\UserController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(CancioneController::class)->group(function () {
    Route::get('canciones', 'list');
    Route::get('canciones/{id}', 'show');
    Route::put('canciones/{id}', 'update');
    Route::delete('canciones/{id}', 'delete');
    Route::post('canciones', 'store');
    Route::get('canciones/var/{id}', 'crearVariacion');
    Route::get('canciones/{nombre}/list', 'listarCancion');
    Route::post('canciones/{id}/rate', 'rate');
});

Route::controller(UserController::class)->group(function () {
    Route::post('users/{id}/favoritos', 'anadirFavoritos');
    Route::delete('users/{id}/favoritos', 'quitarFavoritos');
    Route::get('users/{id}/favoritos', 'verificarFavorito');
    Route::get('users/favoritos/list', 'listarFavoritos');
});

Route::controller(LineaController::class)->group(function () {
    Route::post('lineas', 'store');
});

Route::controller(AcordeController::class)->group(function () {
    Route::get('acordes', 'index');
});
