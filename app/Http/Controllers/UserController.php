<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
<<<<<<< HEAD
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
=======
>>>>>>> origin/backend-Elias

class UserController extends Controller
{
    /**
<<<<<<< HEAD
     * Listado general de usuarios (Exclusivo Secretaría)
     */
    public function index()
    {
        $usuarios = User::with('role')->get();
        // Definimos $users para garantizar total compatibilidad con la vista index.blade.php
        $users = $usuarios; 

        return view('users.index', compact('usuarios', 'users'));
    }

    /**
     * Muestra el formulario para crear un usuario (Exclusivo Secretaría)
     */
    public function create()
{
    $roles = Role::all();
    $registrados = User::count();
    $activos = User::where('USU_ESTADO', 'Activo')->count();

    return view('users.crear-usuario', compact('roles', 'registrados', 'activos')); 
}

    /**
     * Guarda el nuevo usuario (Exclusivo Secretaría)
=======
     * Muestra la lista de usuarios (Docentes, Secretaria, etc.)
     * URL: GET /usuarios (Ruta: usuarios.index)
     */
    public function index()
    {
        $users = User::with('role')->get();
        return view('users.index', compact('users')); // O el string que tenías mientras tanto
    }

    /**
     * Muestra el formulario de registro que hizo Anaís
     * URL: GET /usuarios/create (Ruta: usuarios.create)
     */
    public function create()
    {
        // Traemos los roles para cargarlos en el select del formulario
        $roles = Role::all();
        
        // NOTA: Ajusta 'users.create' según la carpeta real donde Anaís guardó el Blade
        return view('users.create', compact('roles'));
    }

    /**
     * Guarda un nuevo miembro en la base de datos (Ejecutado por la Secretaria)
     * URL: POST /usuarios (Ruta: usuarios.store)
>>>>>>> origin/backend-Elias
     */
    public function store(Request $request)
    {
        // 1. Validamos usando los campos reales de la base de datos de SIGER
        $request->validate([
<<<<<<< HEAD
            'identificacion'   => 'required|numeric|digits_between:7,10|unique:users,USU_CEDULA',
            'name'             => 'required|string|max:50',
            'second-name'      => 'nullable|string|max:50',
            'lastname'         => 'required|string|max:50',
            'second-last-name' => 'nullable|string|max:50',
            'correo'           => 'required|email|unique:users,USU_CORREO',
            'password'         => 'required|string|min:6',
            'rol'              => 'required|exists:roles,id',
=======
            'USU_CEDULA'           => 'required|string|unique:usuarios,USU_CEDULA',
            'USU_PRIMER_NOMBRE'    => 'required|string|max:50',
            'USU_SEGUNDO_NOMBRE'   => 'nullable|string|max:50',
            'USU_PRIMER_APELLIDO'  => 'required|string|max:50',
            'USU_SEGUNDO_APELLIDO' => 'nullable|string|max:50',
            'USU_CORREO'           => 'required|string|email|max:255|unique:usuarios,USU_CORREO',
            'USU_CONTRASEÑA'       => 'required|string|min:6',
            'ROL_ID'               => 'required|exists:roles,id',
>>>>>>> origin/backend-Elias
        ]);

        // 2. Creamos el registro (tu modelo User encripta la contraseña solo)
        User::create([
<<<<<<< HEAD
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
 * Muestra el formulario de edición administrativa (Exclusivo Secretaría)
 */
    public function edit($id)
    {
       $usuario = User::findOrFail($id);
       $roles = Role::all();

      // Contadores dinámicos para la tarjeta lateral
       $registrados = User::count();
       $activos = User::where('USU_ESTADO', 'Activo')->count();

       return view('users.editar-usuario', compact('usuario', 'roles', 'registrados', 'activos'));
    }
    /**
     * Actualiza la información administrativa del usuario (Exclusivo Secretaría)
=======
            'USU_CEDULA'           => $request->USU_CEDULA,
            'USU_PRIMER_NOMBRE'    => $request->USU_PRIMER_NOMBRE,
            'USU_SEGUNDO_NOMBRE'   => $request->USU_SEGUNDO_NOMBRE,
            'USU_PRIMER_APELLIDO'  => $request->USU_PRIMER_APELLIDO,
            'USU_SEGUNDO_APELLIDO' => $request->USU_SEGUNDO_APELLIDO,
            'USU_CORREO'           => $request->USU_CORREO,
            'USU_CONTRASEÑA'       => $request->USU_CONTRASEÑA,
            'USU_ESTADO'           => 'Activo',
            'ROL_ID'               => $request->ROL_ID,
        ]);

        return redirect()->route('usuarios.create')->with('success', 'Personal registrado correctamente.');
    }

    /**
     * Actualiza la información de un usuario
     * URL: PUT/PATCH /usuarios/{id} (Ruta: usuarios.update)
>>>>>>> origin/backend-Elias
     */
    public function update(Request $request, $id)
    {
        $usuario = User::findOrFail($id);

        $request->validate([
<<<<<<< HEAD
            'name'             => 'required|string|max:50',
            'second-name'      => 'nullable|string|max:50',
            'lastname'         => 'required|string|max:50',
            'second-last-name' => 'nullable|string|max:50',
            'correo'           => 'required|email|unique:users,USU_CORREO,' . $usuario->USU_ID . ',USU_ID',
            'rol'              => 'nullable|exists:roles,id',
            'USU_ESTADO'       => 'nullable|string',
        ]);

        $data = [
            'USU_PRIMER_NOMBRE'    => $request->input('name', $usuario->USU_PRIMER_NOMBRE),
            'USU_SEGUNDO_NOMBRE'   => $request->input('second-name', $usuario->USU_SEGUNDO_NOMBRE),
            'USU_PRIMER_APELLIDO'  => $request->input('lastname', $usuario->USU_PRIMER_APELLIDO),
            'USU_SEGUNDO_APELLIDO' => $request->input('second-last-name', $usuario->USU_SEGUNDO_APELLIDO),
            'USU_CORREO'          => $request->input('correo', $usuario->USU_CORREO),
        ];
=======
            'USU_PRIMER_NOMBRE'   => 'required|string|max:50',
            'USU_PRIMER_APELLIDO' => 'required|string|max:50',
            'USU_CORREO'          => 'required|email|unique:usuarios,USU_CORREO,'.$id.',id', // Evita choque con el mismo registro
            'ROL_ID'              => 'required|exists:roles,id',
        ]);

        $user->update([
            'USU_PRIMER_NOMBRE'    => $request->USU_PRIMER_NOMBRE,
            'USU_SEGUNDO_NOMBRE'   => $request->USU_SEGUNDO_NOMBRE,
            'USU_PRIMER_APELLIDO'  => $request->USU_PRIMER_APELLIDO,
            'USU_SEGUNDO_APELLIDO' => $request->USU_SEGUNDO_APELLIDO,
            'USU_CORREO'           => $request->USU_CORREO,
            'ROL_ID'               => $request->ROL_ID,
        ]);
>>>>>>> origin/backend-Elias

        if ($request->filled('rol')) {
            $data['ROL_ID'] = $request->input('rol');
        }

        if ($request->filled('USU_ESTADO')) {
            $data['USU_ESTADO'] = $request->input('USU_ESTADO');
        }

        if ($request->filled('password')) {
            $data['USU_CONTRASEÑA'] = Hash::make($request->input('password'));
        }

        $usuario->update($data);

        return redirect()->route('usuarios.index')->with('success', '¡Información del usuario actualizada correctamente!');
    }

    /**
<<<<<<< HEAD
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

    /**
     * Elimina definitivamente un usuario (Exclusivo Secretaría)
=======
     * Elimina a un usuario del sistema
     * URL: DELETE /usuarios/{id} (Ruta: usuarios.destroy)
>>>>>>> origin/backend-Elias
     */
    public function destroy($id)
    {
        $usuario = User::findOrFail($id);
        $usuario->delete();

        return redirect()->route('usuarios.index')->with('success', '¡Usuario eliminado definitivamente de SIGER!');
    }
}