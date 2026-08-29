<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
     * Procesa la autenticación usando Documento/Cédula y Contraseña
     */
    public function login(Request $request)
    {
        // 1. Validar que la cédula y la contraseña vengan en la petición
        $credentials = $request->validate([
            'USU_CEDULA'     => 'required|string',
            'USU_CONTRASEÑA' => 'required|string',
        ], [
            'USU_CEDULA.required'     => 'El número de documento es obligatorio.',
            'USU_CONTRASEÑA.required' => 'La contraseña es obligatoria.',
        ]);

        // 2. Buscar al usuario por su número de Cédula
        $user = User::where('USU_CEDULA', $credentials['USU_CEDULA'])->first();

        if (!$user) {
            return back()->withErrors([
                'USU_CEDULA' => 'El número de documento no está registrado en el sistema.',
            ])->onlyInput('USU_CEDULA');
        }

        // 3. Validar estado del usuario
        if ($user->USU_ESTADO === 'Inactivo') {
            return back()->withErrors([
                'USU_CEDULA' => 'Tu cuenta se encuentra inactiva. Contacta a la Secretaría.',
            ])->onlyInput('USU_CEDULA');
        }

        // 4. Verificar la contraseña contra el hash de la base de datos
        if (Hash::check($credentials['USU_CONTRASEÑA'], $user->USU_CONTRASEÑA)) {
            
            // Iniciar sesión manualmente para evitar conflictos de nombres de columnas de Laravel
            Auth::login($user);
            $request->session()->regenerate();

            // 5. Redireccionar al dashboard según el Rol asignado
            $rolName = strtolower($user->role->name ?? '');
            $rolSlug = strtolower($user->role->slug ?? '');

            if (in_array($rolName, ['rectora', 'rector']) || in_array($rolSlug, ['rectora', 'rector'])) {
                return redirect()->intended(route('dashboard.rectora'));
            } elseif (in_array($rolName, ['secretaria', 'secretario']) || in_array($rolSlug, ['secretaria', 'secretario'])) {
                return redirect()->intended(route('dashboard.secretaria'));
            } elseif ($rolName === 'docente' || $rolSlug === 'docente') {
                return redirect()->intended(route('dashboard.docente'));
            }

            return redirect()->intended('/dashboard/secretaria');
        }

        // 5. Si la contraseña no coincide
        return back()->withErrors([
            'USU_CONTRASEÑA' => 'La contraseña ingresada es incorrecta.',
        ])->onlyInput('USU_CEDULA');
    }

    /**
     * Cierra la sesión activa
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}