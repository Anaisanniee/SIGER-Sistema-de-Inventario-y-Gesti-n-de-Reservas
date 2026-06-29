<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
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
     */
    public function store(Request $request)
    {
        // 1. Validamos usando los campos reales de la base de datos de SIGER
        $request->validate([
            'USU_CEDULA'           => 'required|string|unique:usuarios,USU_CEDULA',
            'USU_PRIMER_NOMBRE'    => 'required|string|max:50',
            'USU_SEGUNDO_NOMBRE'   => 'nullable|string|max:50',
            'USU_PRIMER_APELLIDO'  => 'required|string|max:50',
            'USU_SEGUNDO_APELLIDO' => 'nullable|string|max:50',
            'USU_CORREO'           => 'required|string|email|max:255|unique:usuarios,USU_CORREO',
            'USU_CONTRASEÑA'       => 'required|string|min:6',
            'ROL_ID'               => 'required|exists:roles,id',
        ]);

        // 2. Creamos el registro (tu modelo User encripta la contraseña solo)
        User::create([
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
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
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

        return redirect()->back()->with('success', 'Personal actualizado correctamente.');
    }

    /**
     * Elimina a un usuario del sistema
     * URL: DELETE /usuarios/{id} (Ruta: usuarios.destroy)
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'Usuario eliminado con éxito.');
    }
}