<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivosControllers;
use App\Http\Controllers\AulasControllers;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReservasControllers;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\InformeController;

// ==========================================
// RUTA DE INVENTARIO
// ==========================================

// Ruta para ver ficha tecnica (Restringida solo a números)
Route::get('/aulas/{id}', [AulasControllers::class, 'show'])->name('aulas.show')->where('id', '[0-9]+');

// ==========================================
// RUTAS DE RESERVAS (Flujo del Docente)
// ==========================================

// Ruta para mostrar el paso 1
Route::get('/reservas/crear/paso1', [ReservasControllers::class, 'paso1'])->name('reservas.paso1');

// Ruta POST que recibe el formulario del paso 1
Route::post('/reservas/crear/paso1', [ReservasControllers::class, 'postPaso1'])->name('reservas.paso1.post');

// Ruta para mostrar el Paso 2
Route::get('/reservas/crear/paso2', [ReservasControllers::class, 'paso2'])->name('reservas.paso2');

// Ruta para procesar el formulario del Paso 2 (POST)
Route::post('/reservas/paso-2', [ReservasControllers::class, 'guardarPaso2'])->name('reservas.paso2.post');

// Ruta para mostrar el Paso 3 (GET)
Route::get('/reservas/crear/paso3', [ReservasControllers::class, 'paso3'])->name('reservas.paso3');

// Ruta para guardar o confirmar la reserva final (POST)
Route::post('/reservas/crear/paso3', [ReservasControllers::class, 'guardarPaso3'])->name('reservas.paso3.post');

// ==========================================
// RUTAS DEL CARRITO
// ==========================================

Route::post('/reservas/guardar-seleccion-temporal', [CarritoController::class, 'guardarSeleccionTemporal'])->name('reservas.guardar.seleccion');


// ==========================================
// OTRAS RUTAS DEL SISTEMA
// ==========================================

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
    // 🔒 EXCLUSIVO SECRETARÍA (Gestión Total y Aprobaciones)
    // -----------------------------------------------------
    Route::middleware('role:Secretaria,Secretario')->group(function () {
        // GESTION DE INVENTARIO
        Route::get('/inventario', [ActivosControllers::class, 'indexUnificado'])->name('inventario.index');

        // GESTIÓN DE RESERVAS (Aprobar, rechazar, revertir y vista de secretaría)
        Route::get('/secretaria/reservas', [ReservasControllers::class, 'indexSecretaria'])->name('secretaria.reservas');
        Route::patch('/secretaria/reservas/{id}/aprobar', [ReservasControllers::class, 'aprobar'])->name('reservas.aprobar');
        Route::patch('/secretaria/reservas/{id}/rechazar', [ReservasControllers::class, 'rechazar'])->name('reservas.rechazar');
        Route::patch('/secretaria/reservas/{id}/revertir', [ReservasControllers::class, 'revertir'])->name('reservas.revertir');

        // GESTIÓN DE USUARIOS
        Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios.index');
        Route::get('/usuarios/create', [UserController::class, 'create'])->name('usuarios.create');
        Route::post('/usuarios', [UserController::class, 'store'])->name('usuarios.store');
        Route::get('/usuarios/{id}/edit', [UserController::class, 'edit'])->name('usuarios.edit');
        Route::put('/usuarios/{id}', [UserController::class, 'update'])->name('usuarios.update');
        Route::patch('/usuarios/{id}/dar-de-baja', [UserController::class, 'darDeBaja'])->name('usuarios.baja');
        Route::delete('/usuarios/{id}', [UserController::class, 'destroy'])->name('usuarios.destroy');

        // RUTAS DE LOS ACTIVOS
        Route::get('/activos/crear', [ActivosControllers::class, 'create'])->name('activos.create');
        Route::get('/inventario/papelera', [ActivosControllers::class, 'trashed'])->name('inventario.papelera');
        Route::post('/activos', [ActivosControllers::class, 'store'])->name('activos.store');
        Route::get('/activos/{id}/editar', [ActivosControllers::class, 'edit'])->name('activos.edit');
        Route::put('/activos/{id}', [ActivosControllers::class, 'update'])->name('activos.update');
        Route::delete('/activos/{id}', [ActivosControllers::class, 'destroy'])->name('activos.destroy');
        Route::post('/activos/{id}/restore', [ActivosControllers::class, 'restore'])->name('activos.restore');
        Route::delete('/activos/{id}/force-delete', [ActivosControllers::class, 'forceDelete'])->name('activos.forceDelete');

        // RUTAS DE LAS AULAS
        Route::get('/aulas/crear', [AulasControllers::class, 'create'])->name('aulas.create');
        Route::post('/aulas', [AulasControllers::class, 'store'])->name('aulas.store');
        Route::get('/aulas/eliminados', [AulasControllers::class, 'trashed'])->name('aulas.trashed');
        Route::get('/aulas/{id}/editar', [AulasControllers::class, 'edit'])->name('aulas.edit');
        Route::put('/aulas/{id}', [AulasControllers::class, 'update'])->name('aulas.update');
        Route::delete('/aulas/{id}', [AulasControllers::class, 'destroy'])->name('aulas.destroy');
        Route::post('/aulas/{id}/restore', [AulasControllers::class, 'restore'])->name('aulas.restore');
        Route::delete('/aulas/{id}/force-delete', [AulasControllers::class, 'forceDelete'])->name('aulas.forceDelete');

        Route::get('/secretaria/informe', [InformeController::class, 'index'])->name('secretaria.informe');

        // Dashboard Secretaría
        Route::get('/dashboard/secretaria', function () {
            return view('dashboard.secretario');
        })->name('dashboard.secretaria');

        Route::get('/dashboard/secretaria-alias', function () {
            return view('dashboard.secretario');
        })->name('dashboard.secretario');
    });

    // -----------------------------------------------------
    // 👑 DASHBOARD RECTORA / RECTOR
    // -----------------------------------------------------
    Route::middleware(['auth', 'role:Rectora,Rector'])->group(function () {
        Route::get('/dashboard/rector', [DashboardController::class, 'indexRector'])->name('dashboard.rectora');
    });

    // -----------------------------------------------------
    // 👨‍🏫 DASHBOARD DOCENTE
    // -----------------------------------------------------
    Route::middleware(['auth', 'role:Docente'])->group(function () {
        Route::get('/dashboard/docente', [DashboardController::class, 'indexDocente'])->name('dashboard.docente');
    });
});