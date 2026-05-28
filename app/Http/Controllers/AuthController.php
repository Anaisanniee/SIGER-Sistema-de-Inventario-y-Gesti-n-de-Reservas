<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Muestra el formulario de login (que harán los del frontend)
    public function showLogin()
    {
        return "Aquí irá el formulario de Login de SIGER";
    }

    // Procesa el intento de entrada con las nuevas columnas
    public function login(Request $request)
    {
        $request->validate([
            'USU_CORREO' => ['required', 'email'],
            'USU_CONTRASEÑA' => ['required'],
        ]);

        $credentials = [
            'USU_CORREO' => $request->USU_CORREO,
            'password' => $request->USU_CONTRASEÑA, 
        ];

        // 3. Intentamos el inicio de sesión
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Traemos el usuario logueado para mostrar un saludo personalizado
            $user = Auth::user();
            return "¡Bienvenido a SIGER, " . $user->USU_PRIMER_NOMBRE . "! Has iniciado sesión correctamente.";
        }

        // Si falla, regresamos con el error apuntando al correo corporativo
        return back()->withErrors([
            'USU_CORREO' => 'Las credenciales no coinciden con nuestros registros.',
        ]);
    }

    // Cerrar sesión
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}