<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\ReservasModels;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Listado general de usuarios (Exclusivo Secretaría)
     */
    public function index(Request $request)
    {
        $query = User::with('role');

        // Si el usuario escribió algo en la barra de búsqueda superior
        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function($q) use ($search) {
                $q->where('USU_CEDULA', 'LIKE', "%{$search}%")
                ->orWhere('USU_PRIMER_NOMBRE', 'LIKE', "%{$search}%")
                ->orWhere('USU_SEGUNDO_NOMBRE', 'LIKE', "%{$search}%")
                ->orWhere('USU_PRIMER_APELLIDO', 'LIKE', "%{$search}%")
                ->orWhere('USU_SEGUNDO_APELLIDO', 'LIKE', "%{$search}%");
            });
        }

        $users = $query->get();
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
            'rol'              => 'required|exists:roles,id',
       ]);

       $cedula = $request->input('identificacion') ?? $request->input('USU_CEDULA');

       // La contraseña inicial será el mismo documento de identidad (cédula)
       $passwordInicial = $cedula;

       User::create([
            'USU_CEDULA'          => $cedula,
            'USU_PRIMER_NOMBRE'   => $request->input('name'),
            'USU_SEGUNDO_NOMBRE'  => $request->input('second-name'),
            'USU_PRIMER_APELLIDO' => $request->input('lastname'),
            'USU_SEGUNDO_APELLIDO'=> $request->input('second-last-name'),
            'USU_CORREO'          => $request->input('correo'),
            'USU_CONTRASEÑA'      => Hash::make($passwordInicial), 
            'ROL_ID'              => $request->input('rol'),
            'USU_ESTADO'          => 'Activo',
       ]);

        return redirect()->route('usuarios.index')->with('success', '¡Usuario creado exitosamente con su documento como contraseña inicial!');
    }

    /**
     * Muestra la vista del perfil del usuario autenticado
     */
    public function perfil()
    {
        $usuario = Auth::user();
        $rol = strtolower($usuario->role->name ?? '');
        
        $pendientesCount = \App\Models\ReservasModels::where('res_estado_reserva', 'pendiente')->count();
        $reservasActivasCount = \App\Models\ReservasModels::where('usu_id', $usuario->usu_id) 
            ->where('res_estado_reserva', 'Aprobada') 
            ->count();

        if (in_array($rol, ['secretario', 'secretaria', 'secretaria general'])) {
            return view('users.perfil.secretario', compact('usuario', 'pendientesCount'));
        }

        if (in_array($rol, ['rector', 'rectora'])) {
            if (view()->exists('users.perfil.rectora')) {
                return view('users.perfil.rectora', compact('usuario', 'pendientesCount', 'reservasActivasCount'));
            }
            if (view()->exists('users.perfil.rector')) {
                return view('users.perfil.rector', compact('usuario', 'pendientesCount', 'reservasActivasCount'));
            }
        }

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
     * Procesa la actualización de la contraseña desde el perfil (Regla Estricta)
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
        ], [
            'password.min' => 'La nueva contraseña debe tener al menos 8 caracteres.',
            'password.mixed_case' => 'La contraseña debe contener al menos una letra mayúscula y una minúscula.',
            'password.numbers' => 'La contraseña debe contener al menos un número.',
            'password.symbols' => 'La contraseña debe contener al menos un símbolo especial.',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->USU_CONTRASEÑA)) {
            return back()->withErrors(['current_password' => 'La contraseña actual no es correcta.']);
        }

        $user->update([
            'USU_CONTRASEÑA' => Hash::make($request->password)
        ]);

        return back()->with('success', '¡Contraseña actualizada correctamente con los requisitos de seguridad!');
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
     * Actualización administrativa del usuario (Permite restablecer a cédula o actualizar datos)
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

        // Opción 1: Si la secretaría marca la casilla para restablecer la clave al documento de identidad
        if ($request->has('restablecer_a_cedula')) {
            if (!empty($usuario->USU_CEDULA)) {
                $data['USU_CONTRASEÑA'] = Hash::make($usuario->USU_CEDULA);
            }
        } 
        // Opción 2: Si la secretaría ingresa una contraseña manual en la edición
        elseif ($request->filled('password')) {
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