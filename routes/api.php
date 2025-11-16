<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Semua route di sini akan otomatis diprefix dengan '/api'
| contoh: http://localhost:8000/api/login
|--------------------------------------------------------------------------
*/

Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::get('/users', [AuthController::class, 'getUsers']);
    Route::get('/users/{id}', [AuthController::class, 'getUserById']);

    
    // New endpoints
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
});

// Only admin can access this endpoint
Route::get('/admin-only', function () {
    return response()->json(['message' => 'Welcome Admin']);
})->middleware(['auth:api', 'role:admin']);

// Only dosen or admin can access
Route::get('/dosen-area', function () {
    return response()->json(['message' => 'Welcome Dosen or Admin']);
})->middleware(['auth:api', 'role:dosen,admin']);

// Only mahasiswa
Route::get('/mahasiswa-area', function () {
    return response()->json(['message' => 'Welcome Mahasiswa']);
})->middleware(['auth:api', 'role:mahasiswa']);

