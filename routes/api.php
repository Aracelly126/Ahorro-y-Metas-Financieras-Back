<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContributionController;
use App\Http\Controllers\GoalController;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/auth/login', [AuthController::class, 'loginsito'])->name('auth.login');
Route::post('/auth/register', [AuthController::class, 'register'])->name('auth.register');


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/auth/profile', [AuthController::class, 'profile'])->name('auth.profile');
    Route::post('/auth/update-photo', [AuthController::class, 'updateProfilePhoto']);
    Route::get('/auth/profile-photo', [AuthController::class, 'getProfilePhoto']);
    Route::post('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::post('/auth/all_logout', [AuthController::class, 'all_logout'])->name('auth.all_logout');

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

    // Contributions routes
    Route::get('/goals/{goal}/contributions', [ContributionController::class, 'index'])->name('contributions.index');
    Route::post('/goals/{goal}/contributions', [ContributionController::class, 'store'])->name('contributions.store');
    Route::get('/goals/{goal}/contributions/{id}', [ContributionController::class, 'show'])->name('contributions.show');
    Route::put('/goals/{goal}/contributions/{id}', [ContributionController::class, 'update'])->name('contributions.update');
    Route::delete('/goals/{goal}/contributions/{id}', [ContributionController::class, 'destroy'])->name('contributions.destroy');
});
