<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ActivosControllers extends Controller
{
    // Muestra el formulario de registro de activos
    public function crear() {
        return view('inventario.crear');
    }

    // Procesa el formulario de activos
    public function storeActivo(Request $request) {
        return back();
    }

    // Vista del catálogo de préstamos de equipos
    public function prestamos() {
        // 🚀 Creamos la variable vacía para que el @forelse del frontend no se rompa
        $activos = []; 

        // Se la mandamos a la vista usando compact
        return view('inventario.prestamos', compact('activos')); 
    }

    // Muestra la vista de edición (Frontend Puro)
    public function edit() {
        return view('inventario.editar');
    }
}