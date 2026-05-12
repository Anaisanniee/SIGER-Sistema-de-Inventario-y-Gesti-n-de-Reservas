<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Muestra la lista de usuarios (Docentes, Secretaria, etc.)
     */
    public function index()
    {
        $users = User::with('role')->get();
        return "Lista de usuarios de SIGER"; 
    }

    /**
     * Guarda un nuevo miembro del personal en la base de datos
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|string|email|max:255|unique:users',
            'document_id' => 'required|string|unique:users',
            'password'    => 'required|string|min:8',
            'role_id'     => 'required|exists:roles,id',
        ]);

        User::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'document_id' => $request->document_id,
            'phone'       => $request->phone,
            'password'    => Hash::make($request->password),
            'role_id'     => $request->role_id,
        ]);

        return redirect()->back()->with('success', 'Personal registrado correctamente.');
    }

    /**
     * Actualiza la información de un docente o administrativo
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,'.$id,
            'document_id' => 'required|unique:users,document_id,'.$id,
            'role_id'     => 'required|exists:roles,id',
        ]);

        $user->update([
            'name'        => $request->name,
            'email'       => $request->email,
            'document_id' => $request->document_id,
            'phone'       => $request->phone,
            'role_id'     => $request->role_id,
        ]);

        return redirect()->back()->with('success', 'Personal actualizado correctamente.');
    }

    /**
     * Elimina a un usuario del sistema
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'Usuario eliminado con éxito.');
    }
} // <--- Esta es la llave que cierra la CLASE y debe ir al final de todo