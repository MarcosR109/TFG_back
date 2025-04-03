<?php

use App\Http\Controllers\CancioneController;
use App\Http\Controllers\LineaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AcordeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/**
 * Las rutas dentro de Route::middleware van a estar protegidas por el middleware jwt, el resto no.
 * */
Route::middleware('jwt')->group(function () {
    Route::controller(CancioneController::class)->group(function () {
        Route::get('canciones', 'list');

        Route::get('canciones/lista/revisar', 'getRevisables');
        Route::put('canciones/{id}/revisar', 'revisar');
        Route::put('canciones/{id}/editar', 'edit');
        Route::get('badge', 'getNrevisables');
        Route::put('canciones/{id}', 'update');
        Route::delete('canciones/{id}', 'delete');
        Route::post('canciones', 'store');
        Route::post('canciones/{id}/rate', 'rate');
        Route::get('canciones/filtrar', 'filtrar');
        Route::get('canciones/var/{id}', 'crearVariacion');
    });
    Route::controller(UserController::class)->group(function () {
        Route::post('users/{id}/favoritos', 'anadirFavoritos');
        Route::delete('users/{id}/favoritos', 'quitarFavoritos');
        Route::get('users/{id}/favoritos', 'verificarFavorito');
        Route::get('users/favoritos/list', 'listarFavoritos');
        Route::get('users/guardados/list', 'listarGuardados');
        Route::put('users/{id}/guardados', 'anadirGuardados');
        Route::delete('users/{id}/guardados', 'quitarGuardados');
        Route::get('users/{id}/guardados', 'verificarGuardados');
    });
});
Route::controller(CancioneController::class)->group(function () {
    Route::get('canciones', 'list');
    Route::get('canciones/{id}', 'show');
    Route::get('canciones/{nombre}/list', 'listarCancion');
    Route::get('canciones/{artist}/lista', 'searchArtista');
});

Route::controller(LineaController::class)->group(function () {
    Route::post('lineas', 'store');
});

Route::controller(AcordeController::class)->group(function () {
    Route::get('acordes', 'index');
});

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::get('logout', 'logout');
    Route::get('refresh', 'refresh');
    Route::get('me', 'me')->middleware('jwt');
});
