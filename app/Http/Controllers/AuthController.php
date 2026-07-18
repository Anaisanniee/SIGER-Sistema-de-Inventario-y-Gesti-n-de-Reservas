<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Muestra la vista del formulario de Login (la que te pasa Ana)
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Procesa el intento de autenticación
     */
    public function login(Request $request)
    {
        // 1. Validamos los datos que llegan del formulario de Ana
        $credentials = $request->validate([
            'USU_CORREO' => 'required|email',
            'USU_CONTRASEÑA' => 'required|string',
        ]);

        // 2. Buscamos primero al usuario para verificar su estado (Regla de negocio)
        $user = User::where('USU_CORREO', $credentials['USU_CORREO'])->first();

        if (!$user) {
            return back()->withErrors([
                'USU_CORREO' => 'Las credenciales no coinciden con nuestros registros.',
            ])->onlyInput('USU_CORREO');
        }

        // 3. Si el usuario fue dado de baja (Inactivo), bloqueamos el acceso de inmediato
        if ($user->USU_ESTADO === 'Inactivo') {
            return back()->withErrors([
                'USU_CORREO' => 'Tu cuenta se encuentra desactivada. Contacta al administrador.',
            ])->onlyInput('USU_CORREO');
        }

        // 4. Intentamos autenticar con Laravel Auth
        // Nota: Laravel por defecto usa 'password', asegúrate de que en tu modelo User 
        // esté el método getAuthPassword() retornando 'USU_CONTRASEÑA' si cambiaste el nombre en la BD.
        if (Auth::attempt(['USU_CORREO' => $credentials['USU_CORREO'], 'password' => $credentials['USU_CONTRASEÑA']])) {
            
            $request->session()->regenerate();

            // 5. Ojo AQUÍ: Redirección automática según el Rol del usuario
            // Suponiendo que tienes una relación 'role' o el campo directo 'ROL_NOMBRE'/'ROL_ID'
            $rol = strtolower($user->role->name ?? ''); // Convertimos a minúsculas para evitar fallos de escritura

            if ($rol === 'rectora') {
                return redirect()->intended('/dashboard/rectora');
            } elseif ($rol === 'secretaria') {
                return redirect()->intended('/dashboard/secretaria');
            } elseif ($rol === 'docente') {
                return redirect()->intended('/dashboard/docente');
            }

            // Redirección por defecto si no tiene un rol claro
            return redirect()->intended('/dashboard');
        }

        // Si la contraseña no coincide
        return back()->withErrors([
            'USU_CORREO' => 'La contraseña ingresada es incorrecta.',
        ])->onlyInput('USU_CORREO');
    }

    /**
     * Cierra la sesión de forma segura
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}