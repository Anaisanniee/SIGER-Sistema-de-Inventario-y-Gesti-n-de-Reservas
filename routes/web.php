<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

// Ruta de bienvenida por defecto
Route::get('/', function () {
    return view('welcome');
});

// 🔑 Rutas de Autenticación (Públicas / Solo para invitados)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// 🚪 Ruta para cerrar sesión (Solo usuarios logueados)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');


// ==========================================
// 🛡️ ZONA PROTEGIDA (DASHBOARDS Y PERMISOS)
// ==========================================
Route::middleware('auth')->group(function () {

    // 1. Bloque exclusivo para la Secretaria
    Route::middleware('role:Secretaria')->group(function () {
        // El CRUD completo de usuarios (Lista, Registro, etc.)
        Route::resource('usuarios', UserController::class);
        
        // El Dashboard de la Secretaria
        Route::get('/dashboard/secretaria', function () {
            return view('dashboards.secretaria');
        })->name('dashboard.secretaria');
    });

    // 2. Bloque exclusivo para la Rectora
    Route::middleware('role:Rectora')->group(function () {
        Route::get('/dashboard/rectora', function () {
            return view('dashboards.rectora');
        })->name('dashboard.rectora');
    });

    // 3. Bloque exclusivo para el Docente
    Route::middleware('role:Docente')->group(function () {
        Route::get('/dashboard/docente', function () {
            return view('dashboards.docente');
        })->name('dashboard.docente');
    });

});