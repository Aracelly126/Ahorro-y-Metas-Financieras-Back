<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GoalController;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/auth/login', [AuthController::class, 'loginsito'])->name('auth.login');
Route::post('/auth/registro', [AuthController::class, 'registro'])->name('auth.registro');


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {return $request->user();});
    Route::post('/auth/perfil', [AuthController::class, 'perfil'])->name('auth.perfil');
    Route::post('/auth/cerrar_sesion', [AuthController::class, 'cerrar_sesion'])->name('auth.cerrar_sesion');
    Route::post('/auth/cerrar_todas_sesion', [AuthController::class, 'cerrar_todas_sesion'])->name('auth.cerrar_todas_sesion');

    Route::get('/goals', [GoalController::class, 'index'])->name('goals.index');
    Route::post('/goals', [GoalController::class, 'store'])->name('goals.store');
    Route::get('/goals/{goal}', [GoalController::class, 'show'])->name('goals.show');
    Route::put('/goals/{goal}', [GoalController::class, 'update'])->name('goals.update');
    Route::patch('/goals/{goal}', [GoalController::class, 'update']);
    Route::delete('/goals/{goal}', [GoalController::class, 'destroy'])->name('goals.destroy');

    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{id}', [CategoryController::class, 'show'])->name('categories.show');
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
});
