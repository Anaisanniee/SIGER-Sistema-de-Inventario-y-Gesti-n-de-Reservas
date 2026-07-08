<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage; // ¡Asegúrate de importar esto arriba!
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
        return view('aulas.crear-aula', compact('tipos'));
    }

    // 3. Guardar nueva aula
    public function store(Request $request)
    {
    // Validación simplificada
        $request->validate([
            'aula_nombre'      => 'required|string|max:25',
            'aula_capacidad'   => 'required|integer|min:1',
            'aula_estado'      => 'required|in:Disponible,Ocupado,En Mantenimiento',
            'tip_aula_id'      => 'required',
            'aula_foto'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->except(['_token', 'aula_foto']);
        $data['aula_reservable'] = $request->has('aula_reservable') ? 1 : 0;

    // Subida de imagen más explícita
        if ($request->hasFile('aula_foto')) {
            $file = $request->file('aula_foto');
        
        // Verificación de integridad
            if ($file->isValid()) {
                $path = $file->store('aulas', 'public');
                $data['aula_foto'] = $path;
            }
        }

        AulasModels::create($data);

        return redirect()->route('inventario.index_unificado')->with('success', 'Aula creada correctamente.');
    }

    // 4. Mostrar formulario de edición
    public function edit($id)
    {
        $aula = AulasModels::findOrFail($id);
        $tipos = TiposAulasModels::all();
        return view('aulas.editar-aula', compact('aula', 'tipos'));
    }

    // 5. Actualizar aula
    public function update(Request $request, $id)
    {
        // 1. Buscamos el aula
        $aula = AulasModels::findOrFail($id);

        // 2. Validamos campos obligatorios (sin mencionar la foto aquí)
        $request->validate([
            'aula_nombre'    => 'required',
            'aula_capacidad' => 'required|numeric',
            'aula_estado'    => 'required',
            'tip_aula_id'    => 'required',
        ]);

        // 3. Asignación directa de campos
        $aula->aula_nombre    = $request->aula_nombre;
        $aula->aula_capacidad = $request->aula_capacidad;
        $aula->aula_estado    = $request->aula_estado;
        $aula->tip_aula_id    = $request->tip_aula_id;
        $aula->aula_reservable = $request->has('aula_reservable') ? 1 : 0;

        // 4. Lógica de FOTO (Simplificada al máximo)
        if ($request->hasFile('aula_foto')) {
            
            // Eliminamos el $request->validate() que estaba causando el error.
            // Verificamos directamente si es un archivo válido antes de guardar.
            if ($request->file('aula_foto')->isValid()) {
                
                // Borrar anterior
                if ($aula->aula_foto && \Storage::disk('public')->exists($aula->aula_foto)) {
                    \Storage::disk('public')->delete($aula->aula_foto);
                }

                // Guardar la nueva
                $path = $request->file('aula_foto')->store('aulas', 'public');
                $aula->aula_foto = $path;
            }
        }

        // 5. Guardar
        $aula->save();

        return redirect()->route('inventario.index_unificado')->with('success', 'Aula actualizada con éxito.');
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

        return redirect()->route('inventario.index_unificado')->with('success', 'Aula dada de baja exitosamente.');
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
        
        return redirect()->route('inventario.index_unificado')->with('success', 'Aula restaurada.');
    }
}