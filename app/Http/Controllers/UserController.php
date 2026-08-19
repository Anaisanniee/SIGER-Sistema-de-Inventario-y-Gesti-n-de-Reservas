<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Listado general de usuarios (Exclusivo Secretaría)
     */
    public function index()
    {
        $usuarios = User::with('role')->get();

        return view('users.index', compact('usuarios'));
    }

    /**
     * Muestra el formulario para crear un usuario (Exclusivo Secretaría)
     */
    public function create()
    {
        $roles = Role::all();

        return view('users.crear-usuario', compact('roles')); 
    }

    /**
     * Guarda el nuevo usuario (Exclusivo Secretaría)
     */
    public function store(Request $request)
    {
        $request->validate([
            'USU_CEDULA'           => 'required|numeric|digits_between:7,10|unique:users,USU_CEDULA',
            'USU_PRIMER_NOMBRE'    => 'required|string|max:50',
            'USU_SEGUNDO_NOMBRE'   => 'nullable|string|max:50',
            'USU_PRIMER_APELLIDO'  => 'required|string|max:50',
            'USU_SEGUNDO_APELLIDO' => 'nullable|string|max:50',
            'USU_CORREO'           => 'required|email|unique:users,USU_CORREO',
            'USU_CONTRASEÑA'       => 'required|string|min:6',
            'ROL_ID'               => 'required|exists:roles,id',
        ]);

        User::create([
            'USU_CEDULA'           => $request->USU_CEDULA,
            'USU_PRIMER_NOMBRE'    => $request->USU_PRIMER_NOMBRE,
            'USU_SEGUNDO_NOMBRE'   => $request->USU_SEGUNDO_NOMBRE,
            'USU_PRIMER_APELLIDO'  => $request->USU_PRIMER_APELLIDO,
            'USU_SEGUNDO_APELLIDO' => $request->USU_SEGUNDO_APELLIDO,
            'USU_CORREO'           => $request->USU_CORREO,
            'USU_CONTRASEÑA'       => Hash::make($request->USU_CONTRASEÑA),
            'ROL_ID'               => $request->ROL_ID,
            'USU_ESTADO'           => 'Activo',
        ]);

        return redirect()->route('usuarios.index')->with('success', '¡Usuario creado exitosamente!');
    }

    /**
     * Muestra la vista del perfil adaptada según el rol del usuario autenticado
     */
  public function perfil()
{
    $usuario = auth()->user();
    $rol = strtolower($usuario->role->name ?? '');

    // Secretaría
    if (in_array($rol, ['secretario', 'secretaria', 'secretaria general'])) {
        return view('users.perfil.secretario', compact('usuario'));
    }

    // Rector / Rectora (Busca 'rectora', luego 'rector', o usa la vista por defecto)
    if (in_array($rol, ['rector', 'rectora'])) {
        if (view()->exists('users.perfil.rectora')) {
            return view('users.perfil.rectora', compact('usuario'));
        }
        if (view()->exists('users.perfil.rector')) {
            return view('users.perfil.rector', compact('usuario'));
        }
    }

    // Vista general para Docente o cualquier otro rol
    return view('users.perfil.perfil-usuario', compact('usuario'));
}
    /**
     * Muestra el formulario de edición (Secretaría, Rectora y Docente)
     */
    public function edit($id)
    {
        $usuario = User::findOrFail($id);
        $roles = Role::all();

        return view('users.editar-usuario', compact('usuario', 'roles'));
    }

    /**
     * Actualiza la información del usuario (Secretaría, Rectora y Docente)
     */
    public function update(Request $request, $id)
    {
        $usuario = User::findOrFail($id);

        $request->validate([
            'USU_CEDULA'           => 'required|numeric|digits_between:7,10|unique:users,USU_CEDULA,' . $usuario->USU_ID . ',USU_ID',
            'USU_PRIMER_NOMBRE'    => 'required|string|max:50',
            'USU_SEGUNDO_NOMBRE'   => 'nullable|string|max:50',
            'USU_PRIMER_APELLIDO'  => 'required|string|max:50',
            'USU_SEGUNDO_APELLIDO' => 'nullable|string|max:50',
            'USU_CORREO'           => 'required|email|unique:users,USU_CORREO,' . $usuario->USU_ID . ',USU_ID',
            'ROL_ID'               => 'required|exists:roles,id',
        ]);

        $data = [
            'USU_CEDULA'           => $request->USU_CEDULA,
            'USU_PRIMER_NOMBRE'    => $request->USU_PRIMER_NOMBRE,
            'USU_SEGUNDO_NOMBRE'   => $request->USU_SEGUNDO_NOMBRE,
            'USU_PRIMER_APELLIDO'  => $request->USU_PRIMER_APELLIDO,
            'USU_SEGUNDO_APELLIDO' => $request->USU_SEGUNDO_APELLIDO,
            'USU_CORREO'           => $request->USU_CORREO,
            'ROL_ID'               => $request->ROL_ID,
        ];

        // Solo si se ingresó una nueva contraseña se encripta y actualiza
        if ($request->filled('USU_CONTRASEÑA')) {
            $data['USU_CONTRASEÑA'] = Hash::make($request->USU_CONTRASEÑA);
        }

        $usuario->update($data);

        return redirect()->back()->with('success', '¡Información actualizada correctamente!');
    }

    /**
     * Alterna el estado del usuario (Exclusivo Secretaría)
     */
    public function darDeBaja($id)
    {
        $usuario = User::findOrFail($id);
        $nuevoEstado = ($usuario->USU_ESTADO === 'Activo') ? 'Inactivo' : 'Activo';
        
        $usuario->update([
            'USU_ESTADO' => $nuevoEstado
        ]);

        return redirect()->back()->with('success', "Estado del usuario cambiado a {$nuevoEstado}.");
    }
}