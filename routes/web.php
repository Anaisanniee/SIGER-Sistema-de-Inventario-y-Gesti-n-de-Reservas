<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

// Ruta de bienvenida pública
Route::get('/', function () {
    return view('welcome');
});

// 🔑 Rutas de Autenticación (Públicas / Solo para invitados)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// 🚪 Cerrar sesión (Solo usuarios autenticados)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');


// =========================================================
// 🛡️ ZONA PROTEGIDA (REQUERIDO LOGIN Y AUTORIZACIÓN)
// =========================================================
Route::middleware('auth')->group(function () {

    // -----------------------------------------------------
    // 🔒 EXCLUSIVO SECRETARÍA (Creación, Listado y Baja)
    // -----------------------------------------------------
    Route::middleware('role:Secretaria')->group(function () {
        // Listado general de usuarios
        Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios.index');

        // Formulario y guardado exclusivo de usuarios
        Route::get('/usuarios/create', [UserController::class, 'create'])->name('usuarios.create');
        Route::post('/usuarios', [UserController::class, 'store'])->name('usuarios.store');

        // Cambiar estado de usuario (Activo / Inactivo)
        Route::patch('/usuarios/{id}/dar-de-baja', [UserController::class, 'darDeBaja'])->name('usuarios.baja');

        // Dashboard Secretaría
        Route::get('/dashboard/secretaria', function () {
            return view('dashboards.secretaria');
        })->name('dashboard.secretaria');
    });

    // -----------------------------------------------------
    // 🔓 EDICIÓN DE USUARIOS (Secretaría, Rectora y Docente)
    // -----------------------------------------------------
    Route::middleware('role:Secretaria,Rectora,Docente')->group(function () {
        Route::get('/usuarios/{id}/edit', [UserController::class, 'edit'])->name('usuarios.edit');
        Route::put('/usuarios/{id}', [UserController::class, 'update'])->name('usuarios.update');
    });

    // -----------------------------------------------------
    // 👑 DASHBOARD RECTORA
    // -----------------------------------------------------
    Route::middleware('role:Rectora')->group(function () {
        Route::get('/dashboard/rectora', function () {
            return view('dashboards.rectora');
        })->name('dashboard.rectora');
    });

    // -----------------------------------------------------
    // 👨‍🏫 DASHBOARD DOCENTE
    // -----------------------------------------------------
    Route::middleware('role:Docente')->group(function () {
        Route::get('/dashboard/docente', function () {
            return view('dashboards.docente');
        })->name('dashboard.docente');
    });

});