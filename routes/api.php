<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/auth/login', [AuthController::class, 'loginsito'])->name('auth.login');
Route::post('/auth/registro', [AuthController::class, 'registro'])->name('auth.registro');


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {return $request->user();});
    Route::post('/auth/perfil', [AuthController::class, 'perfil'])->name('auth.perfil');
    Route::post('/auth/cerrar_sesion', [AuthController::class, 'cerrar_sesion'])->name('auth.cerrar_sesion');
    Route::post('/auth/cerrar_todas_sesion', [AuthController::class, 'cerrar_todas_sesion'])->name('auth.cerrar_todas_sesion');
});
