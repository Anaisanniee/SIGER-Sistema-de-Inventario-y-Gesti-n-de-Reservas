<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\ReservasModels;
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
        $users = User::with('role')->get();
        $usuarios = $users; 

        return view('users.index', compact('users', 'usuarios'));
    }

    /**
     * Muestra el formulario para crear un usuario
     */
    public function create()
    {
        $roles = Role::all();
        $registrados = User::count();
        $activos = User::where('USU_ESTADO', 'Activo')->count();

        if (view()->exists('users.crear-usuario')) {
            return view('users.crear-usuario', compact('roles', 'registrados', 'activos'));
        }

        return view('users.create', compact('roles', 'registrados', 'activos')); 
    }

    /**
     * Guarda el nuevo usuario
     */
    public function store(Request $request)
    {
        $request->validate([
            'identificacion'   => 'nullable|numeric|digits_between:7,10|unique:users,USU_CEDULA',
            'USU_CEDULA'       => 'nullable|string|unique:users,USU_CEDULA',
            'name'             => 'required|string|max:50',
            'second-name'      => 'nullable|string|max:50',
            'lastname'         => 'required|string|max:50',
            'second-last-name' => 'nullable|string|max:50',
            'correo'           => 'required|email|unique:users,USU_CORREO',
            'password'         => 'required|string|min:6',
            'rol'              => 'required|exists:roles,id',
        ]);

        $cedula = $request->input('identificacion') ?? $request->input('USU_CEDULA');

        User::create([
            'USU_CEDULA'          => $cedula,
            'USU_PRIMER_NOMBRE'   => $request->input('name'),
            'USU_SEGUNDO_NOMBRE'  => $request->input('second-name'),
            'USU_PRIMER_APELLIDO' => $request->input('lastname'),
            'USU_SEGUNDO_APELLIDO'=> $request->input('second-last-name'),
            'USU_CORREO'          => $request->input('correo'),
            'USU_CONTRASEÑA'      => Hash::make($request->input('password')),
            'ROL_ID'              => $request->input('rol'),
            'USU_ESTADO'          => 'Activo',
        ]);

        return redirect()->route('usuarios.index')->with('success', '¡Usuario creado exitosamente!');
    }

    /**
     * Muestra la vista del perfil del usuario autenticado
     */
    public function perfil()
    {
        $usuario = Auth::user();
        $rol = strtolower($usuario->role->name ?? '');
        
        // 1. Conteo global para secretaría (reservas pendientes en todo el sistema)
        $pendientesCount = \App\Models\ReservasModels::where('res_estado_reserva', 'pendiente')->count();

        // 2. Conteo específico para el usuario logueado (Reservas activas / aprobadas suyas)
        // Ajusta 'user_id' o 'res_usuario_id' según el nombre exacto de la columna en tu tabla de reservas
        $reservasActivasCount = \App\Models\ReservasModels::where('usu_id', $usuario->usu_id) 
            ->where('res_estado_reserva', 'Aprobada') 
            ->count();

        if (in_array($rol, ['secretario', 'secretaria', 'secretaria general'])) {
            return view('users.perfil.secretario', compact('usuario', 'pendientesCount'));
        }

        if (in_array($rol, ['rector', 'rectora'])) {
            // Al rector también le puedes pasar el indicador si lo necesita en su tarjeta
            if (view()->exists('users.perfil.rectora')) {
                return view('users.perfil.rectora', compact('usuario', 'pendientesCount', 'reservasActivasCount'));
            }
            if (view()->exists('users.perfil.rector')) {
                return view('users.perfil.rector', compact('usuario', 'pendientesCount', 'reservasActivasCount'));
            }
        }

        // Para el Docente y perfil general de usuario
        return view('users.perfil.perfil-usuario', compact('usuario', 'pendientesCount', 'reservasActivasCount'));
    }

    /**
     * Actualiza la información del perfil propio
     */
    public function updatePerfil(Request $request)
    {
        $usuario = Auth::user();

        $request->validate([
            'name'             => 'required|string|max:50',
            'second-name'      => 'nullable|string|max:50',
            'lastname'         => 'required|string|max:50',
            'second-last-name' => 'nullable|string|max:50',
            'correo'           => 'required|email|unique:users,USU_CORREO,' . $usuario->usu_id . ',usu_id',
        ]);

        $usuario->update([
            'USU_PRIMER_NOMBRE'   => $request->input('name'),
            'USU_SEGUNDO_NOMBRE'  => $request->input('second-name'),
            'USU_PRIMER_APELLIDO' => $request->input('lastname'),
            'USU_SEGUNDO_APELLIDO'=> $request->input('second-last-name'),
            'USU_CORREO'          => $request->input('correo'),
        ]);

        return redirect()->back()->with('success', '¡Perfil actualizado correctamente!');
    }

    /**
     * Edición administrativa de usuario
     */
    public function edit($id)
    {
        $usuario = User::findOrFail($id);
        $roles = Role::all();

        $registrados = User::count();
        $activos = User::where('USU_ESTADO', 'Activo')->count();

        return view('users.editar-usuario', compact('usuario', 'roles', 'registrados', 'activos'));
    }

    /**
     * Actualización administrativa del usuario
     */
    public function update(Request $request, $id)
    {
        $usuario = User::findOrFail($id);

        $request->validate([
            'name'             => 'required|string|max:50',
            'lastname'         => 'required|string|max:50',
            'correo'           => 'required|email|unique:users,USU_CORREO,' . $id . ',usu_id',
            'rol'              => 'nullable|exists:roles,id',
        ]);

        $data = [
            'USU_PRIMER_NOMBRE'   => $request->input('name', $usuario->USU_PRIMER_NOMBRE),
            'USU_SEGUNDO_NOMBRE'  => $request->input('second-name', $usuario->USU_SEGUNDO_NOMBRE),
            'USU_PRIMER_APELLIDO' => $request->input('lastname', $usuario->USU_PRIMER_APELLIDO),
            'USU_SEGUNDO_APELLIDO'=> $request->input('second-last-name', $usuario->USU_SEGUNDO_APELLIDO),
            'USU_CORREO'          => $request->input('correo', $usuario->USU_CORREO),
        ];

        if ($request->filled('rol')) {
            $data['ROL_ID'] = $request->input('rol');
        }

        if ($request->filled('password')) {
            $data['USU_CONTRASEÑA'] = Hash::make($request->input('password'));
        }

        $usuario->update($data);

        return redirect()->route('usuarios.index')->with('success', '¡Usuario actualizado correctamente!');
    }

    /**
     * Alterna el estado Activo/Inactivo
     */
    public function darDeBaja($id)
    {
        $usuario = User::findOrFail($id);
        $nuevoEstado = ($usuario->USU_ESTADO === 'Activo') ? 'Inactivo' : 'Activo';

        $usuario->update(['USU_ESTADO' => $nuevoEstado]);

        return redirect()->back()->with('success', "Estado del usuario cambiado a {$nuevoEstado}.");
    }

    /**
     * Elimina definitivamente a un usuario
     */
    public function destroy($id)
    {
        $usuario = User::findOrFail($id);
        $usuario->delete();

        return redirect()->route('usuarios.index')->with('success', '¡Usuario eliminado definitivamente de SIGER!');
    }
}