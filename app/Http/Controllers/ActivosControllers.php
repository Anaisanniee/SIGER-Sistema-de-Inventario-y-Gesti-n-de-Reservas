<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AulasModels;
use App\Models\CategoriasModels;
use App\Models\ActivosModels;
use Illuminate\Support\Facades\Storage;

class ActivosControllers extends Controller
{
    public function indexUnificado(Request $request)
    {
        $buscar = trim($request->get('buscar'));
        $filtroCategoria = $request->get('categoria');
        $categorias = CategoriasModels::all();

        // --- LÓGICA DE ACTIVOS ---
        $query = ActivosModels::with(['aula', 'categoria']);

        if ($buscar) {
            $query->where(function($q) use ($buscar) {
                $q->where('act_nombre', 'LIKE', '%' . $buscar . '%')
                ->orWhere('act_serial', 'LIKE', '%' . $buscar . '%');
            });
        }

        if ($filtroCategoria) {
            $query->where('cate_id', $filtroCategoria);
        }

        $activos = $query->orderBy('act_id', 'desc')->get()->map(function($activo) {
            $activo->tipo_recurso = 'activo'; 
            return $activo;
        });

        // --- LÓGICA DE AULAS ---
        $queryAulas = AulasModels::query();
        
        // Si hay búsqueda, filtramos también las aulas
        if ($buscar) {
            $queryAulas->where('aula_nombre', 'LIKE', '%' . $buscar . '%');
        }

        $aulas = $queryAulas->get()->map(function($aula) {
            $aula->tipo_recurso = 'aula';
            return $aula;
        });

        // UNIFICAMOS todo
        $recursos = $activos->concat($aulas)->shuffle();

        return view('inventario.index', compact(
            'recursos', 
            'categorias', 
            'buscar'
        ));
    }

    public function show($id)
    {
        $activo = ActivosModels::with(['aula', 'categoria'])->findOrFail($id);
        return view('activos.show', compact('activo'));
    }

    public function create()
    {
        $aulas = AulasModels::all();
        $categorias = CategoriasModels::all();
        return view('activos.crear-activo', compact('aulas', 'categorias'));
    }

    // ====================================================================
    // MÉTODO DESTROY (Guarda el motivo ANTES de aplicar el SoftDelete)
    // ====================================================================
    public function destroy(Request $request, $id)
    {
        // 1. Buscar el activo por su ID
        $activo = ActivosModels::findOrFail($id);

        // 2. Capturar el motivo que viene desde el SweetAlert2
        $activo->act_motivo_baja = $request->input('act_motivo_baja');
        
        // 3. ¡IMPORTANTE! Guardar en la BD mientras el registro sigue activo
        $activo->save(); 

        // 4. Ahora sí, aplicar el SoftDelete (pone la fecha en deleted_at)
        $activo->delete();

        return redirect()->back()->with('mensaje', 'Activo enviado a la papelera con su respectivo motivo de baja.');
    }

    public function edit($id)
    {
        $activo = ActivosModels::findOrFail($id);
        $aulas = AulasModels::all();
        $categorias = CategoriasModels::all();
        return view('activos.editar-activos', compact('activo', 'aulas', 'categorias'));
    }

    public function update(Request $request, $id)
    {
        $activo = ActivosModels::findOrFail($id);

        $request->validate([
            'act_nombre'        => 'required|string|min:3|max:50',
            'act_serial'        => 'required|string|unique:activos,act_serial,' . $id . ',act_id',
            'act_marca'         => 'nullable|string|max:50',
            'aula_id'           => 'required|exists:aulas,aula_id',
            'cate_id'           => 'required|exists:categorias,cate_id',
            'act_estado_fisico' => 'required|string|max:50',
            'act_reservable'    => 'required|boolean',
            'act_fecha_ingreso' => 'required|date',
        ], [
            'act_nombre.min'      => 'El nombre debe tener al menos 3 letras.',
            'act_nombre.required' => 'Escribe el nombre del activo.',
            'act_serial.unique'   => 'Este serial ya pertenece a otro activo registrado.',
            'act_serial.required' => 'Escribe el serial del activo.',
        ]);

        if ($request->hasFile('act_foto')) {
            if ($activo->act_foto) {
                Storage::disk('public')->delete($activo->act_foto);
            }
            $activo->act_foto = $request->file('act_foto')->store('activos', 'public');
        }

        $activo->act_nombre        = $request->act_nombre;
        $activo->act_serial        = $request->act_serial;
        $activo->act_marca         = $request->act_marca; 
        $activo->aula_id           = $request->aula_id;
        $activo->cate_id           = $request->cate_id;
        $activo->act_estado_fisico = $request->act_estado_fisico;
        $activo->act_reservable    = $request->act_reservable;
        $activo->act_fecha_ingreso = $request->act_fecha_ingreso;
    
        $activo->save(); 

        return redirect()->route('inventario.index_unificado')->with('mensaje', 'Activo actualizado correctamente');
    }

    public function store(Request $request)
    {
        $request->validate([
            'act_nombre'        => 'required|string|min:3|max:50',
            'act_serial'        => 'required|string|unique:activos,act_serial', 
            'act_marca'         => 'nullable|string|max:50',
            'aula_id'           => 'required|exists:aulas,aula_id',        
            'cate_id'           => 'required|exists:categorias,cate_id',   
            'act_estado_fisico' => 'required|string|max:50',
            'act_reservable'    => 'required|boolean',
            'act_fecha_ingreso' => 'required|date',
            'act_foto'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'act_nombre.min'             => 'El nombre debe tener al menos 3 letras.',
            'act_nombre.required'        => 'Escribe el nombre del activo.',
            'act_serial.unique'          => 'Este serial ya existe en el sistema.',
            'aula_id.exists'             => 'El aula seleccionada no existe.',
            'cate_id.exists'             => 'La categoría seleccionada no existe.',
            'act_estado_fisico.required' => 'Seleccione el estado físico del activo.',
        ]);

        try {
            $activo = new ActivosModels();
            $activo->act_nombre        = $request->act_nombre;
            $activo->act_serial        = $request->act_serial;
            $activo->act_marca         = $request->act_marca;
            $activo->aula_id           = $request->aula_id;
            $activo->cate_id           = $request->cate_id;
            $activo->act_estado_fisico = $request->act_estado_fisico;
            $activo->act_reservable    = $request->act_reservable;
            $activo->act_fecha_ingreso = $request->act_fecha_ingreso;

            if ($request->hasFile('act_foto')) {
                $activo->act_foto = $request->file('act_foto')->store('activos', 'public');
            }

            $activo->save();
            return redirect()->route('inventario.index_unificado')->with('mensaje', 'Activo creado con éxito.');
        
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error técnico: ' . $e->getMessage());
        }
    }

    // Métodos de la papelera
    public function trashed()
    {
        $activos = ActivosModels::onlyTrashed()->with(['aula', 'categoria'])->orderBy('deleted_at', 'desc')->get();
        $total = $activos->count();
        return view('activos.eliminados', compact('activos', 'total'));
    }

    public function restore($id)
    {
        $activo = ActivosModels::onlyTrashed()->findOrFail($id);
        $activo->restore();
        return redirect()->route('inventario.index_unificado')->with('mensaje', 'Activo restaurado con éxito.');
    }

    public function forceDelete($id)
    {
        $activo = ActivosModels::onlyTrashed()->findOrFail($id);
        if ($activo->act_foto) {
            Storage::disk('public')->delete($activo->act_foto);
        }
        $activo->forceDelete();
        return redirect()->back()->with('mensaje', 'Activo eliminado permanentemente.');
    }
}