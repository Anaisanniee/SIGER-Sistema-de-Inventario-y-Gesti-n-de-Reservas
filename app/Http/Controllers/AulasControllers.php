<?php

namespace App\Http\Controllers;

use App\Models\AulasModels;
use App\Models\TiposAulasModels;
use Illuminate\Http\Request;

class AulasControllers extends Controller
{
    // 1. Listar aulas activas
    public function index()
    {
        $aulas = AulasModels::all();
        return view('aulas.index', compact('aulas'));
    }

    // 1.5. Mostrar ficha técnica (detalle)
    public function show($id)
    {
        $aula = AulasModels::findOrFail($id);
        return view('aulas.show', compact('aula'));
    }

    // 2. Mostrar formulario de creación
    public function create()
    {
        $tipos = TiposAulasModels::all();
        return view('aulas.create', compact('tipos'));
    }

    // 3. Guardar nueva aula
    public function store(Request $request)
    {
        $validated = $request->validate([
            'aula_nombre'      => 'required|string|max:25',
            'aula_capacidad'   => 'required|integer|min:1',
            // Restringimos a los valores del select
            'aula_estado'      => 'required|in:Disponible,Ocupado,En Mantenimiento',
            'aula_reservable'  => 'nullable', 
            'tip_aula_id'      => 'required|exists:tipos_aulas,tip_aula_id',
        ]);

        $validated['aula_reservable'] = $request->has('aula_reservable') ? 1 : 0;

        AulasModels::create($validated);

        return redirect()->route('aulas.index')->with('success', 'Aula creada correctamente.');
    }

    // 4. Mostrar formulario de edición
    public function edit($id)
    {
        $aula = AulasModels::findOrFail($id);
        $tipos = TiposAulasModels::all();
        return view('aulas.edit', compact('aula', 'tipos'));
    }

    // 5. Actualizar aula
    public function update(Request $request, $id)
    {
        $aula = AulasModels::findOrFail($id);
    
        $validated = $request->validate([
            'aula_nombre'      => 'required|string|max:25',
            'aula_capacidad'   => 'required|integer|min:1',
            'aula_estado'      => 'required|in:Disponible,Ocupado,En Mantenimiento',
            // Quitamos 'nullable' y dejamos el campo abierto a recibir el valor
            'aula_reservable'  => 'required', 
            'tip_aula_id'      => 'required|exists:tipos_aulas,tip_aula_id',
        ]);

        // Lógica explícita: Si el valor enviado es '1', guardamos 1, sino 0.
        $aula->aula_reservable = $request->aula_reservable == '1' ? 1 : 0;
    
        // Asignamos el resto de los campos
        $aula->aula_nombre = $validated['aula_nombre'];
        $aula->aula_capacidad = $validated['aula_capacidad'];
        $aula->aula_estado = $validated['aula_estado'];
        $aula->tip_aula_id = $validated['tip_aula_id'];

        $aula->save();

        return redirect()->route('aulas.index')->with('success', 'Aula actualizada con éxito.');
    }

    // 6. SoftDelete (Dar de baja)
    public function destroy(Request $request, $id)
    {
        $aula = AulasModels::findOrFail($id);
        
        $request->validate([
            'aula_motivo_baja' => 'required|string|min:5|max:255'
        ]);
        
        $aula->aula_motivo_baja = $request->aula_motivo_baja;
        $aula->save();
        
        $aula->delete();

        return redirect()->route('aulas.index')->with('success', 'Aula dada de baja exitosamente.');
    }

    // 7. Listar elementos en la papelera
    public function trashed()
    {
        $aulas = AulasModels::onlyTrashed()->get();
        return view('aulas.papelera', compact('aulas'));
    }

    // 8. Restaurar aula
    public function restore($id)
    {
        $aula = AulasModels::withTrashed()->findOrFail($id);
        $aula->restore();
        
        return redirect()->route('aulas.index')->with('success', 'Aula restaurada.');
    }
}