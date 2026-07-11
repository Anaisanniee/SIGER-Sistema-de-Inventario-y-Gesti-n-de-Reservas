<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ActivosModels;
use App\Models\AulasModels;

class DashboardController extends Controller
{
    public function indexDocente()
    {
        // Cambia Activo por ActivosModels y Aula por AulasModels
        $recursos = ActivosModels::all()->concat(AulasModels::all());
        
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
        
        // Lógica para el secretario
        return view('dashboard.secretario');
    }
}