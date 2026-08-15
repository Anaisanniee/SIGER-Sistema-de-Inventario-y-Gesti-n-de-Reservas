<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Muestra la vista del formulario de Login
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Procesa el intento de autenticación y redirige según el Rol
     */
    public function login(Request $request)
    {
        // 1. Validaciones
        $credentials = $request->validate([
            'USU_CORREO'     => 'required|email',
            'USU_CONTRASEÑA' => 'required|string',
        ]);

        // 2. Verificar existencia del usuario
        $user = User::where('USU_CORREO', $credentials['USU_CORREO'])->first();

        if (!$user) {
            return back()->withErrors([
                'USU_CORREO' => 'Las credenciales no coinciden con nuestros registros.',
            ])->onlyInput('USU_CORREO');
        }

        // 3. Regla de negocio: Estado Inactivo
        if ($user->USU_ESTADO === 'Inactivo') {
            return back()->withErrors([
                'USU_CORREO' => 'Tu cuenta se encuentra desactivada. Contacta al administrador.',
            ])->onlyInput('USU_CORREO');
        }

        // 4. Intento de autenticación
        if (Auth::attempt(['USU_CORREO' => $credentials['USU_CORREO'], 'password' => $credentials['USU_CONTRASEÑA']])) {
            
            $request->session()->regenerate();

            // 5. Redirección por NOMBRE del Rol (string, sin IDs numéricos)
            $rol = strtolower($user->role->name ?? '');

            if ($rol === 'rectora' || $rol === 'rector') {
                return redirect()->intended('/dashboard/rectora');
            } elseif ($rol === 'secretaria' || $rol === 'secretario') {
                return redirect()->intended('/dashboard/secretaria');
            } elseif ($rol === 'docente') {
                return redirect()->intended('/dashboard/docente');
            }

            return redirect()->intended('/dashboard');
        }

        // 6. Contraseña incorrecta
        return back()->withErrors([
            'USU_CORREO' => 'La contraseña ingresada es incorrecta.',
        ])->onlyInput('USU_CORREO');
    }

    /**
     * Cierra la sesión
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}