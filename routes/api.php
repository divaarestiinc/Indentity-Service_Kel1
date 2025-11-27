<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminUserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Semua route di sini otomatis memakai prefix '/api'
|--------------------------------------------------------------------------
*/

// ----------------------
// AUTH PUBLIC ROUTES
// ----------------------
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);


// ===================================================================
// PROTECTED ROUTES (HARUS LOGIN JWT)
// ===================================================================
Route::middleware('auth:api')->group(function () {

    // -------- AUTHENTICATION --------
    Route::get('/me',        [AuthController::class, 'me']);
    Route::post('/logout',   [AuthController::class, 'logout']);
    Route::post('/refresh',  [AuthController::class, 'refresh']);
    Route::middleware('auth:api')->get('/me', [AuthController::class, 'me']);


    // -------- BASIC USER INFO --------
    Route::get('/users',        [AuthController::class, 'getUsers']);
    Route::get('/users/{id}',   [AuthController::class, 'getUserById']);

    // ===================================================================
    // ADMIN USER MANAGEMENT
    // ===================================================================
    Route::prefix('admin')
        ->middleware('role:admin_prodi,admin_poli') // dua jenis admin
        ->group(function () {

            Route::get('/users',        [AdminUserController::class, 'index']);
            Route::post('/users',       [AdminUserController::class, 'store']);
            Route::get('/users/{id}',   [AdminUserController::class, 'show']);
            Route::put('/users/{id}',   [AdminUserController::class, 'update']);
            Route::delete('/users/{id}',[AdminUserController::class, 'destroy']);
    });

});
