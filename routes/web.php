<?php

use App\Http\Controllers\ActivosControllers;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservasControllers;

// --- RUTA RAÍZ (BIENVENIDA) ---
// Ahora la raíz cargará tu dashboard de una
Route::get('/', function () {
    return view('dashboard.index-dashboard'); 
})->name('home');

// --- RUTA DEL DASHBOARD PRINCIPAL ---
Route::get('/dashboard', function () {
    return view('dashboard.index-dashboard');
})->name('dashboard');


// --- MÓDULO DE AULAS (RESERVAS) ---
// Vista del catálogo de aulas (Le asignamos nombre para estandarizar)
Route::get('/reservas', [ReservasControllers::class, 'index'])->name('reservas.index');

// Formulario para registrar una nueva aula
Route::get('/aulas/crear', [ReservasControllers::class, 'create'])->name('aulas.create');

// Acción para procesar y guardar el aula en la base de datos
Route::post('/aulas/guardar', [ReservasControllers::class, 'store'])->name('aulas.store');


// --- MÓDULO DE ACTIVOS (INVENTARIO) ---
// Formulario para registrar un nuevo activo o equipo
Route::get('/activos/crear', [ReservasControllers::class, 'createActivo'])->name('activos.create');

// Acción para procesar y guardar el activo en la base de datos
Route::post('/activos/guardar', [ReservasControllers::class, 'storeActivo'])->name('activos.store');

// Vista del catálogo de préstamos de equipos (El prototipo que acabamos de enlazar)
Route::get('/prestamos/equipos', [ActivosControllers::class, 'prestamos'])->name('inventario.prestamos');