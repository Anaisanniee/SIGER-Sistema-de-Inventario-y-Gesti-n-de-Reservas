<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

// Bloque de seguridad exclusivo para la Secretaria (ID 2)
// Aquí se valida que esté logueado (auth) y que sea Secretaria (role:2)
Route::middleware(['auth', 'role:2'])->group(function () {
    Route::resource('usuarios', UserController::class);
});

// 🔑 Rutas de Autenticación comunes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');