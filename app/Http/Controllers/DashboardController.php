<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ActivosModels;
use App\Models\AulasModels;

class DashboardController extends Controller
{
    public function indexDocente()
    {
        // 1. Cargamos activos normalmente
        $activos = ActivosModels::all();
        
        // 2. Cargamos las aulas con la relación 'categoria' incluida desde el principio
        $aulas = AulasModels::with('categoria', 'activos')->get();
        
        // 3. Concatenamos ambas colecciones ya cargadas
        $recursos = $activos->concat($aulas);
        
        // 4. Pasamos $recursos a la vista
        return view('dashboard.docente', compact('recursos'));
    }

    public function indexRector()
    {
        // Buscamos los datos igual que en docente
        $recursos = ActivosModels::all()->concat(AulasModels::all());
        
        // Pasamos la variable $recursos a la vista
        return view('dashboard.rector', compact('recursos'));
}

    public function indexSecretario()
    {
        // Consultamos todas las reservas con sus relaciones necesarias (detalles, activos, aulas y usuario)
        // Esto es vital para que la función de mapeo no falle al buscar los nombres y fotos
        $reservas = \App\Models\ReservasModels::with(['detalles.activo', 'detalles.aula', 'usuario'])
                        ->orderBy('res_id', 'desc')
                        ->get();

        // Pasamos la variable $reservas a la vista
        return view('dashboard.secretario', compact('reservas'));
    }
}