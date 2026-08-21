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
            'identificacion'   => 'required|numeric|digits_between:7,10|unique:users,USU_CEDULA',
            'name'             => 'required|string|max:50',
            'second-name'      => 'nullable|string|max:50',
            'lastname'         => 'required|string|max:50',
            'second-last-name' => 'nullable|string|max:50',
            'correo'           => 'required|email|unique:users,USU_CORREO',
            'password'         => 'required|string|min:6',
            'rol'              => 'required|exists:roles,id',
        ]);

        User::create([
            'USU_CEDULA'           => $request->input('identificacion'),
            'USU_PRIMER_NOMBRE'    => $request->input('name'),
            'USU_SEGUNDO_NOMBRE'   => $request->input('second-name'),
            'USU_PRIMER_APELLIDO'  => $request->input('lastname'),
            'USU_SEGUNDO_APELLIDO' => $request->input('second-last-name'),
            'USU_CORREO'          => $request->input('correo'),
            'USU_CONTRASEÑA'       => Hash::make($request->input('password')),
            'ROL_ID'               => $request->input('rol'),
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

        // Rector / Rectora
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
     * 🛡️ CAPA 2 DE SEGURIDAD: Actualiza de forma estricta los datos propios del perfil.
     * Solo permite la edición de nombres, apellidos y correo del usuario autenticado.
     */
    public function updatePerfil(Request $request)
    {
        $usuario = Auth::user();

        // 1. Validar únicamente los campos autorizados para el perfil
        $request->validate([
            'name'             => 'required|string|max:50',
            'second-name'      => 'nullable|string|max:50',
            'lastname'         => 'required|string|max:50',
            'second-last-name' => 'nullable|string|max:50',
            'correo'           => 'required|email|unique:users,USU_CORREO,' . $usuario->USU_ID . ',USU_ID',
        ]);

        // 2. Extraer de forma estricta solo los inputs permitidos (Se ignoran 'rol', 'estado' o 'identificacion')
        $datosFiltrados = [
            'USU_PRIMER_NOMBRE'    => $request->input('name'),
            'USU_SEGUNDO_NOMBRE'   => $request->input('second-name'),
            'USU_PRIMER_APELLIDO'  => $request->input('lastname'),
            'USU_SEGUNDO_APELLIDO' => $request->input('second-last-name'),
            'USU_CORREO'          => $request->input('correo'),
        ];

        // 3. Actualizar únicamente el registro del usuario autenticado
        $usuario->update($datosFiltrados);

        return redirect()->back()->with('success', '¡Perfil actualizado correctamente!');
    }

    /**
     * Muestra el formulario de edición administrativa (Secretaría, Rectora y Docente)
     */
    public function edit($id)
    {
        $usuario = User::findOrFail($id);
        $roles = Role::all();

        return view('users.editar-usuario', compact('usuario', 'roles'));
    }

    /**
     * Actualiza la información administrativa del usuario
     */
    public function update(Request $request, $id)
    {
        $usuario = User::findOrFail($id);

        $request->validate([
            'identificacion'   => 'required|numeric|digits_between:7,10|unique:users,USU_CEDULA,' . $usuario->USU_ID . ',USU_ID',
            'name'             => 'required|string|max:50',
            'second-name'      => 'nullable|string|max:50',
            'lastname'         => 'required|string|max:50',
            'second-last-name' => 'nullable|string|max:50',
            'correo'           => 'required|email|unique:users,USU_CORREO,' . $usuario->USU_ID . ',USU_ID',
            'rol'              => 'required|exists:roles,id',
            'estado'           => 'nullable|string',
        ]);

        $data = [
            'USU_CEDULA'           => $request->input('identificacion'),
            'USU_PRIMER_NOMBRE'    => $request->input('name'),
            'USU_SEGUNDO_NOMBRE'   => $request->input('second-name'),
            'USU_PRIMER_APELLIDO'  => $request->input('lastname'),
            'USU_SEGUNDO_APELLIDO' => $request->input('second-last-name'),
            'USU_CORREO'          => $request->input('correo'),
            'ROL_ID'               => $request->input('rol'),
        ];

        if ($request->has('estado')) {
            $data['USU_ESTADO'] = $request->input('estado');
        }

        if ($request->filled('password')) {
            $data['USU_CONTRASEÑA'] = Hash::make($request->input('password'));
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