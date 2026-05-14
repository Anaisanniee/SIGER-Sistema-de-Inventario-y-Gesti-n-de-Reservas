<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

// Solo los que hayan iniciado sesión pueden gestionar usuarios
Route::middleware(['auth'])->group(function () {
    Route::resource('usuarios', UserController::class);
});

// Solo la secretaria (ID 2) puede entrar aquí
Route::middleware(['auth', 'role:2'])->group(function () {
    Route::resource('usuarios', UserController::class);
});

// Rutas de Autenticación
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');