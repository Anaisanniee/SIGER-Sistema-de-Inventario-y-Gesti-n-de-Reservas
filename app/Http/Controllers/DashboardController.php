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
        // Consultamos las reservas incluyendo con trashed las relaciones para evitar "N/A"
        $reservas = \App\Models\ReservasModels::with([
                'detalles.activo' => fn($q) => $q->withTrashed(), 
                'detalles.aula' => fn($q) => $q->withTrashed(), 
                'usuario'
            ])
            ->orderBy('res_id', 'desc')
            ->get();

        return view('dashboard.secretario', compact('reservas'));
    }
}