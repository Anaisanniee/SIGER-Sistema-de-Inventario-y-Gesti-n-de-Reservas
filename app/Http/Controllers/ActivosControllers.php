<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AulasModels;
use App\Models\CategoriasModels;
use App\Models\ActivosModels;
use Illuminate\Support\Facades\Storage;

class ActivosControllers extends Controller
{
    public function index(Request $request)
    {
    // 1. Obtenemos los parámetros de búsqueda y filtro
    $buscar = trim($request->get('buscar'));
    $filtroCategoria = $request->get('categoria');

    // 2. Cargamos todas las categorías para el menú desplegable
    $categorias = CategoriasModels::all();

    // 3. Iniciamos la consulta con sus relaciones (Eager Loading)
    $query = ActivosModels::with(['aula', 'categoria']);

    // 4. Filtro por nombre o serial
    if ($buscar) {
        $query->where(function($q) use ($buscar) {
            $q->where('act_nombre', 'LIKE', '%' . $buscar . '%')
                ->orWhere('act_serial', 'LIKE', '%' . $buscar . '%');
        });
    }

    // 5. NUEVO: Aplicar el filtro de categoría a la consulta
    if ($filtroCategoria) {
        $query->where('cate_id', $filtroCategoria);
    }

    // 6. Ejecutamos la consulta
    $activos = $query->orderBy('act_id', 'desc')->get();

    // 7. Contamos los resultados para el contador de la vista
    $total = $activos->count();

    // 8. RETORNO: Es vital incluir 'categorias', 'total' y 'buscar' en el compact
    return view('activos.index', compact('activos', 'categorias', 'total', 'buscar'));
    }

    public function show($id)
    {
    // El 'with' trae la información del aula de una vez
        $activo = ActivosModels::with('aula')->findOrFail($id);
        return view('activos.show', compact('activo'));
    }

    public function create()
    {
        $aulas = AulasModels::all();
        $categorias = CategoriasModels::all();
    
        return view('activos.crear', compact('aulas', 'categorias'));
    }

    public function destroy($id)
    {
        // Buscar el activo por su ID
        $activo = ActivosModels::findOrFail($id);

        // Eliminar el registro de la base de datos
        $activo->delete();

        // Redireccionar con un mensaje de éxito
        return redirect()->back()->with('success', 'Activo eliminado correctamente.');
    }

    public function edit($id)
    {
        $activo = ActivosModels::findOrFail($id);
        $aulas = AulasModels::all();
        $categorias = CategoriasModels::all();
    
        return view('activos.editar', compact('activo', 'aulas', 'categorias'));
    }

    public function update(Request $request, $id)
    {
        // 1. Buscar el registro
        $activo = ActivosModels::findOrFail($id);

    // 2. Validar (Ajusta los nombres según tu base de datos)
        $request->validate([
            'act_nombre' => 'required|string|min:3|max:255',
            'act_serial' => 'required|unique:activos,act_serial,' . $id . ',act_id',
            'aula_id'    => 'required',
            'cate_id'    => 'required',
        ], [
            'act_nombre.min'      => 'El nombre debe tener al menos 3 letras.',
            'act_nombre.required' => 'Escribe el nombre del activo.',
        ]);

    // 3. Procesar la foto (opcional)
        if ($request->hasFile('act_foto')) {
            $activo->act_foto = $request->file('act_foto')->store('activos', 'public');
        }

    // 4. Actualizar todos los campos
        $activo->act_nombre = $request->act_nombre;
        $activo->act_serial = $request->act_serial;
        $activo->aula_id = $request->aula_id;
        $activo->cate_id = $request->cate_id;
    
        $activo->save(); // <-- ¡IMPORTANTE! Si no llamas a save(), no se guarda nada.

        return redirect()->route('activos.index')->with('success', 'Activo actualizado correctamente');
    }

    public function store(Request $request)
    {
        // Definición de reglas
        $request->validate([
            'act_nombre'        => 'required|string|min:3|max:50',
            'act_serial'        => 'required|string|unique:activos,act_serial', 
            'aula_id'           => 'required|exists:aulas,AULA_ID',
            'cate_id'           => 'required|exists:categorias,CATE_ID',
            'act_estado_fisico' => 'required|in:Nuevo,Bueno,Regular,Malo',
        ], [
            'act_nombre.min'      => 'El nombre debe tener al menos 3 letras.',
            'act_nombre.required' => 'Escribe el nombre del activo.',
            'act_serial.unique'   => 'Este serial ya existe.',
            'act_serial.string'   => 'El serial debe ser un texto válido.', // Traducción por si acaso
            'aula_id.exists'      => 'Ese ID de aula no existe en el sistema.',
            'cate_id.exists'      => 'Ese ID de categoría no existe en el sistema.',
        ]);

        try {
            $datos = $request->all();
        
            if ($request->hasFile('act_foto')) {
                $datos['act_foto'] = $request->file('act_foto')->store('activos', 'public');
            }

            $datos['act_fecha_ingreso'] = now();
            $datos['act_reservable'] = $request->act_reservable ?? 1;

            ActivosModels::create($datos);

            return redirect()->route('activos.index')->with('mensaje', 'Activo creado con éxito.');
        
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error técnico: ' . $e->getMessage());
        }
    }
}