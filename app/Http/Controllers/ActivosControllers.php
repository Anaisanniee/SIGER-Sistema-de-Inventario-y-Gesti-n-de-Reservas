<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivosModels;
use Illuminate\Support\Facades\Storage;

class ActivosControllers extends Controller
{
    public function index()
    {
        $activos = ActivosModels::all(); 
        // Volvemos a la ruta simple
        return view('activos.index', compact('activos'));
    }

    public function create()
    {
        // Volvemos a la ruta simple
        return view('activos.crear');
    }

    public function store(Request $request)
    {
        // 1. Validación: Asegúrate de que los 'name' del formulario coincidan aquí
        $request->validate([
            'act_nombre' => 'required|string|max:50',
            'act_serial' => 'required|unique:activos,act_serial',
            'act_foto'   => 'nullable|image|max:2048',
            'aula_id'    => 'required',
            'cate_id'    => 'required',
        ]);

        try {
            $datosActivo = $request->all();

            // 2. Procesar Multimedia
            if ($request->hasFile('act_foto')) {
                $datosActivo['act_foto'] = $request->file('act_foto')->store('activos', 'public');
            }

            // 3. Valores automáticos
            $datosActivo['act_fecha_ingreso'] = now();
            $datosActivo['act_reservable'] = $request->act_reservable ?? 1;

            // 4. Guardar
            ActivosModels::create($datosActivo);

            return redirect()->route('activos.index')->with('mensaje', '¡Activo registrado exitosamente!');

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error al guardar: ' . $e->getMessage());
        }
    }
}