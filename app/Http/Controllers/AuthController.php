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
     * Procesa el intento de autenticación por Cédula/Documento y redirige según el Rol
     */
    public function login(Request $request)
    {
        // 1. Validaciones
        $credentials = $request->validate([
            'USU_CEDULA'     => 'required|string',
            'USU_CONTRASEÑA' => 'required|string',
        ], [
            'USU_CEDULA.required'     => 'El número de documento es obligatorio.',
            'USU_CONTRASEÑA.required' => 'La contraseña es obligatoria.',
        ]);

        // 2. Verificar existencia del usuario por número de Cédula/Documento
        $user = User::where('USU_CEDULA', $credentials['USU_CEDULA'])->first();

        if (!$user) {
            return back()->withErrors([
                'USU_CEDULA' => 'Las credenciales no coinciden con nuestros registros.',
            ])->onlyInput('USU_CEDULA');
        }

        // 3. Regla de negocio: Estado Inactivo
        if ($user->USU_ESTADO === 'Inactivo') {
            return back()->withErrors([
                'USU_CEDULA' => 'Tu cuenta se encuentra desactivada. Contacta al administrador.',
            ])->onlyInput('USU_CEDULA');
        }

        // 4. Intento de autenticación con USU_CEDULA
        if (Auth::attempt(['USU_CEDULA' => $credentials['USU_CEDULA'], 'password' => $credentials['USU_CONTRASEÑA']])) {
            
            $request->session()->regenerate();

            // 5. Redirección por NOMBRE del Rol
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
            'USU_CEDULA' => 'La contraseña ingresada es incorrecta.',
        ])->onlyInput('USU_CEDULA');
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