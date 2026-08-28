<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivosControllers;
use App\Http\Controllers\AulasControllers;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReservasControllers;
use App\Http\Controllers\CarritoController;
// ==========================================
// RUTA DE INVENTARIO
// ==========================================

// Ruta para ver el listado
Route::get('/inventario', [ActivosControllers::class, 'indexUnificado'])->name('inventario.index');

// ==========================================
// RUTAS DE ACTIVOS (INVENTARIO)
// ==========================================

// Ruta para ver el formulario
Route::get('/activos/crear', [ActivosControllers::class, 'create'])->name('activos.create');

// NUEVA: Vista de la papelera / eliminados (Va arriba de las de {id} para evitar conflictos)
Route::get('/inventario/papelera', [ActivosControllers::class, 'trashed'])->name('inventario.papelera');

// Ruta para procesar el guardado
Route::post('/activos', [ActivosControllers::class, 'store'])->name('activos.store');

// Ruta para mostrar el formulario de edición
Route::get('/activos/{id}/editar', [ActivosControllers::class, 'edit'])->name('activos.edit');

// Ruta para procesar la actualización (PUT o PATCH)
Route::put('/activos/{id}', [ActivosControllers::class, 'update'])->name('activos.update');

// Ruta para el borrado lógico (Soft Delete)
Route::delete('/activos/{id}', [ActivosControllers::class, 'destroy'])->name('activos.destroy');

// NUEVA: Restaurar un activo eliminado
Route::post('/activos/{id}/restore', [ActivosControllers::class, 'restore'])->name('activos.restore');

// NUEVA: Borrado permanente de la base de datos (Físico)
Route::delete('/activos/{id}/force-delete', [ActivosControllers::class, 'forceDelete'])->name('activos.forceDelete');

// ==========================================
// RUTAS DE AULAS (INVENTARIO)
// ==========================================

// Ruta para ver el formulario de creación
Route::get('/aulas/crear', [AulasControllers::class, 'create'])->name('aulas.create');

// Ruta para procesar el guardado
Route::post('/aulas', [AulasControllers::class, 'store'])->name('aulas.store');

// Ruta para ver la papelera
Route::get('/aulas/eliminados', [AulasControllers::class, 'trashed'])->name('aulas.trashed');

// Ruta para mostrar el formulario de edición
Route::get('/aulas/{id}/editar', [AulasControllers::class, 'edit'])->name('aulas.edit');

// Ruta para procesar la actualización
Route::put('/aulas/{id}', [AulasControllers::class, 'update'])->name('aulas.update');

// Ruta para el borrado lógico (Soft Delete)
Route::delete('/aulas/{id}', [AulasControllers::class, 'destroy'])->name('aulas.destroy');

// Ruta para restaurar un aula
Route::post('/aulas/{id}/restore', [AulasControllers::class, 'restore'])->name('aulas.restore');

// Ruta para borrado permanente (Físico)
Route::delete('/aulas/{id}/force-delete', [AulasControllers::class, 'forceDelete'])->name('aulas.forceDelete');

// Ruta para ver ficha tecnica
Route::get('/aulas/{id}', [AulasControllers::class, 'show'])->name('aulas.show');

// ==========================================
// RUTAS DE RESERVAS
// ==========================================

Route::get('/secretaria/reservas', [ReservasControllers::class, 'indexSecretaria'])->name('secretaria.reservas');

// Ruta para mostrar el paso 1 (Ya no necesita {id?} ni {tipo?})
Route::get('/reservas/crear/paso1', [ReservasControllers::class, 'paso1'])->name('reservas.paso1');

// Ruta POST que recibe el formulario del paso 1 (Tampoco necesita {id})
Route::post('/reservas/crear/paso1', [ReservasControllers::class, 'postPaso1'])->name('reservas.paso1.post');

// Ruta para mostrar el Paso 2
Route::get('/reservas/crear/paso2', [ReservasControllers::class, 'paso2'])->name('reservas.paso2');

// Ruta para procesar el formulario del Paso 2 (POST)
Route::post('/reservas/paso-2', [ReservasControllers::class, 'guardarPaso2'])->name('reservas.paso2.post');

// Ruta para mostrar el Paso 3 (GET)
Route::get('/reservas/crear/paso3', [ReservasControllers::class, 'paso3'])->name('reservas.paso3');

// Ruta para guardar o confirmar la reserva final (POST)
Route::post('/reservas/crear/paso3', [ReservasControllers::class, 'guardarPaso3'])->name('reservas.paso3.post');

// Ruta para aprobar reserva
Route::patch('/secretaria/reservas/{id}/aprobar', [ReservasControllers::class, 'aprobar'])->name('reservas.aprobar');

// Ruta para rechazar reserva
Route::patch('/secretaria/reservas/{id}/rechazar', [ReservasControllers::class, 'rechazar'])->name('reservas.rechazar');

// Ruta para revertir reserva
Route::patch('/secretaria/reservas/{id}/revertir', [ReservasControllers::class, 'revertir'])->name('reservas.revertir');

// ==========================================
// RUTAS DEL CARRITO
// ==========================================

Route::post('/reservas/guardar-seleccion-temporal', [CarritoController::class, 'guardarSeleccionTemporal'])->name('reservas.guardar.seleccion');

// ==========================================
// RUTAS DEL DASHBOARD
// ==========================================

// Rutas de Dashboard sin middleware para poder trabajar libremente
Route::get('/dashboard/docente', [DashboardController::class, 'indexDocente'])->name('dashboard.docente');

Route::get('/dashboard/rector', [DashboardController::class, 'indexRector'])->name('dashboard.rector');

Route::get('/dashboard/secretario', [DashboardController::class, 'indexSecretario'])->name('dashboard.secretario');

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
    // 🔒 EXCLUSIVO SECRETARÍA (Gestión Total de Usuarios)
    // -----------------------------------------------------
    Route::middleware('role:Secretaria,Secretario')->group(function () {
        // Listado general y creación
        Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios.index');
        Route::get('/usuarios/create', [UserController::class, 'create'])->name('usuarios.create');
        Route::post('/usuarios', [UserController::class, 'store'])->name('usuarios.store');
        
        // Edición administrativa exclusiva
        Route::get('/usuarios/{id}/edit', [UserController::class, 'edit'])->name('usuarios.edit');
        Route::put('/usuarios/{id}', [UserController::class, 'update'])->name('usuarios.update');
        
        // Acciones de estado y borrado
        Route::patch('/usuarios/{id}/dar-de-baja', [UserController::class, 'darDeBaja'])->name('usuarios.baja');
        Route::delete('/usuarios/{id}', [UserController::class, 'destroy'])->name('usuarios.destroy');

        // Dashboard Secretaría
        Route::get('/dashboard/secretaria', function () {
            return view('dashboard.secretario');
        })->name('dashboard.secretaria');
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