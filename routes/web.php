<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivosControllers;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

// Ruta para ver el listado
Route::get('/activos', [ActivosControllers::class, 'index'])->name('activos.index');

// Ruta para ver el formulario
Route::get('/activos/crear', [ActivosControllers::class, 'create'])->name('activos.create');

// Ruta para procesar el guardado
Route::post('/activos', [ActivosControllers::class, 'store'])->name('activos.store');
// Ruta para mostrar el formulario de edición
Route::get('/activos/{id}/editar', [ActivosControllers::class, 'edit'])->name('activos.edit');

// Ruta para procesar la actualización (fíjate que es PUT o PATCH)
Route::put('/activos/{id}', [ActivosControllers::class, 'update'])->name('activos.update');
Route::delete('/activos/{id}', [ActivosControllers::class, 'destroy'])->name('activos.destroy');


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

?>
