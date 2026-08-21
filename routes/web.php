<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

// Ruta de bienvenida pública
Route::get('/', function () {
    return view('welcome');
});

// 🔑 Rutas de Autenticación (Públicas)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// 🚪 Cerrar sesión oficial (POST)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// 🚪 Ruta rápida por GET para cerrar sesión desde la URL (solo para pruebas)
Route::get('/logout-dev', [AuthController::class, 'logout']);


// =========================================================
// 🛡️ ZONA PROTEGIDA (REQUERIDO LOGIN Y AUTORIZACIÓN)
// =========================================================
Route::middleware('auth')->group(function () {
    
    // 👤 PERFIL DE USUARIO (Accesible para cualquier usuario autenticado)
    Route::get('/perfil', [UserController::class, 'perfil'])->name('perfil');
    
    // 🛡️ CAPA 2 DE SEGURIDAD: Ruta exclusiva para actualizar datos propios del perfil
    Route::put('/perfil/actualizar', [UserController::class, 'updatePerfil'])->name('perfil.actualizar');

    // -----------------------------------------------------
    // 🔒 EXCLUSIVO SECRETARÍA
    // -----------------------------------------------------
    Route::middleware('role:Secretaria,Secretario')->group(function () {
        Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios.index');
        Route::get('/usuarios/create', [UserController::class, 'create'])->name('usuarios.create');
        Route::post('/usuarios', [UserController::class, 'store'])->name('usuarios.store');
        Route::patch('/usuarios/{id}/dar-de-baja', [UserController::class, 'darDeBaja'])->name('usuarios.baja');

        // Dashboard Secretaría
        Route::get('/dashboard/secretaria', function () {
            return view('dashboard.secretario');
        })->name('dashboard.secretaria');
    });

    // -----------------------------------------------------
    // 🔓 EDICIÓN DE USUARIOS (Administrativo)
    // -----------------------------------------------------
    Route::middleware('role:Secretaria,Secretario,Rectora,Rector,Docente')->group(function () {
        Route::get('/usuarios/{id}/edit', [UserController::class, 'edit'])->name('usuarios.edit');
        Route::put('/usuarios/{id}', [UserController::class, 'update'])->name('usuarios.update');
    });

    // -----------------------------------------------------
    // 👑 DASHBOARD RECTORA / RECTOR
    // -----------------------------------------------------
    Route::middleware('role:Rectora,Rector')->group(function () {
        Route::get('/dashboard/rectora', function () {
            $recursos = []; 
            return view('dashboard.rector', compact('recursos'));
        })->name('dashboard.rectora');
    });

    // -----------------------------------------------------
    // 👨‍🏫 DASHBOARD DOCENTE
    // -----------------------------------------------------
    Route::middleware('role:Docente')->group(function () {
        Route::get('/dashboard/docente', function () {
            $recursos = []; 
            return view('dashboard.docente', compact('recursos'));
        })->name('dashboard.docente');
    });

});