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
        // 1. Validamos los datos
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|string|email|max:255|unique:users',
            'document_id' => 'required|string|unique:users',
            'password'    => 'required|string|min:8',
            'role_id'     => 'required|exists:roles,id',
        ]);

        // 2. Creamos al usuario
        User::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'document_id' => $request->document_id,
            'phone'       => $request->phone,
            'password'    => Hash::make($request->password),
            'role_id'     => $request->role_id,
        ]);

        // 3. Respuesta
        return redirect()->back()->with('success', 'Personal registrado correctamente en SIGER.');
    }
}
