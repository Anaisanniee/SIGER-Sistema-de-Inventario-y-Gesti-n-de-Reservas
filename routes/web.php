<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivosControllers;
use App\Http\Controllers\AulasControllers;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

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
// RUTAS DEL DASHBOARD
// ==========================================

// Rutas de Dashboard sin middleware para poder trabajar libremente
Route::get('/dashboard/docente', [DashboardController::class, 'indexDocente'])->name('dashboard.docente');

Route::get('/dashboard/rector', [DashboardController::class, 'indexRector'])->name('dashboard.rector');

Route::get('/dashboard/secretario', [DashboardController::class, 'indexSecretario'])->name('dashboard.secretario');

// ==========================================
// OTRAS RUTAS DEL SISTEMA
// ==========================================

Route::get('/', function () {
    return view('welcome');
});

// Bloque de seguridad exclusivo para la Secretaria (ID 2)
// Aquí se valida que esté logueado (auth) y que sea Secretaria (role:2)
Route::middleware(['auth', 'role:Secretaria'])->group(function () {
    Route::resource('usuarios', UserController::class);
});

// 🔑 Rutas de Autenticación comunes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');



// ==========================================
// RUTAS DE PRUEBA (NO TOCAR)
// ==========================================
Route::get('/prueba', function () {
    return view('informes.inventario', [
        'usuario' => auth()->user() ?? new \App\Models\User()
    ]);
});



// Si quieres usar la ruta /prueba temporalmente:
Route::get('/pruebas', [UserController::class, 'index']);

// O la ruta definitiva para el módulo de usuarios:
Route::get('/usuarios', [UserController::class, 'index'])->name('users.index');