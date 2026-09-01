<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InformeController extends Controller
{
    /**
     * Muestra la vista del informe general de la institución.
     */
    public function index()
    {
        // Aquí luego puedes consultar datos de reservas, activos o aulas si lo requieres
        return view('components.tablas.tabla-informe');
    }
}