<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontendAuthController;
use App\Http\Controllers\FrontendUserController;

// Default route ke login
Route::get('/', function () {
     return view('auth.login');
});

// Dashboard Laravel default (jika sudah pakai auth bawaan)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Default Laravel Profile (Breeze / Jetstream)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


/* =========================================================
|  🚀  Tambahan Route Frontend Manual (Tanpa Breeze)
|  Menghubungkan ke API sebagai frontend login/register
|=========================================================*/

// Login + Register (Frontend)
Route::get('/login', [FrontendAuthController::class, 'showLoginForm'])->name('login.api');
Route::post('/login', [FrontendAuthController::class, 'login']);
Route::get('/register', [FrontendAuthController::class, 'showRegisterForm'])->name('register.api');
Route::post('/register', [FrontendAuthController::class, 'register']);

// Logout
Route::get('/logout', [FrontendAuthController::class, 'logout'])->name('logout.api');

// Halaman profil user dari API (bukan profile bawaan Laravel Breeze)
Route::middleware('auth.frontend')->group(function () {
    Route::get('/user-profile', [FrontendUserController::class, 'profile'])->name('user.profile');
});
