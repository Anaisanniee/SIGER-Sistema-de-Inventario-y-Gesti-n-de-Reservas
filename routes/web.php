<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

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


Route::get('/pruebas', function () {
    
    // 1. RECURSO TIPO: ACTIVO
    $activo = new \stdClass();
    $activo->act_id = 101; // Clave esencial para el @if de la vista
    $activo->act_foto = null; // null usará 'default.jpeg' según tu Blade
    $activo->act_nombre = 'Proyector Epson';
    $activo->act_serial = 'EPS-X49-98765';
    $activo->act_estado_fisico = 'Buen Estado';
    $activo->act_reservable = true;
    $activo->act_marca = 'Epson';
    $activo->act_fecha_ingreso = '2026-01-10';
    $activo->act_precio_actual = 450.00;
    $activo->precio_actual = 450.00; // Lo pide el modal con este nombre
    $activo->aula_nombre = 'Auditorio Principal';
    $activo->cate_id = 3;

    // 2. RECURSO TIPO: AULA
    $aula = new \stdClass();
    // No definimos 'act_id' para que la vista lo procese en el @else
    $aula->aula_id = 12;
    $aula->aula_foto = null; // null usará 'default.jpeg' de aulas
    $aula->aula_nombre = 'Laboratorio';
    $aula->aula_capacidad = 25;
    $aula->aula_estado = 'Disponible';
    $aula->aula_reservable = true;
    $aula->tip_aula_id = 1;

    // 3. Empaquetamos ambos elementos en la colección que espera el Blade
    $recursos = collect([$activo, $aula]);

    // 4. Retornamos la vista pasando la variable $recursos
    // (Asegúrate de cambiar 'pruebas' por el nombre real de tu archivo .blade.php)
    return view('pruebas', compact('recursos'));
});