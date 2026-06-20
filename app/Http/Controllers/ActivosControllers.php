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
        return view('inventario.prestamos'); 
    }

    // 🚀 Muestra la vista de edición (Frontend Puro)
    public function edit() {
        return view('inventario.editar');
    }
}