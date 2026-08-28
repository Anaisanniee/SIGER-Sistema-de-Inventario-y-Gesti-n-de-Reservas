<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    /**
<<<<<<< HEAD
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
            $userAuthenticated = Auth::user();
            $rol = strtolower($userAuthenticated->role->name ?? '');

            if (in_array($rol, ['rectora', 'rector'])) {
                return redirect()->intended(route('dashboard.rectora'));
            } elseif (in_array($rol, ['secretaria', 'secretario'])) {
                return redirect()->intended(route('dashboard.secretaria'));
            } elseif ($rol === 'docente') {
                return redirect()->intended(route('dashboard.docente'));
            }

            return redirect()->intended('/');
        } // <- Se cierra el bloque de autenticación exitosa

        // 6. Si Auth::attempt falla, la contraseña es incorrecta
        return back()->withErrors([
            'USU_CEDULA' => 'La contraseña ingresada es incorrecta.',
        ])->onlyInput('USU_CEDULA');
    }

    /**
     * Cierra la sesión
=======
     * Muestra el formulario de login.
     */
    public function showLogin()
    {
        return view('auth.login'); 
    }

    /**
     * Procesa el intento de entrada de forma automática, valida credenciales
     * encriptadas y redirige al dashboard correspondiente de cada rol.
     */
    public function login(Request $request)
    {
        // 1. Validamos únicamente que el correo y la contraseña sean enviados
        $request->validate([
            'USU_CORREO' => 'required|email',
            'USU_CONTRASEÑA' => 'required',
        ]);

        // 2. Preparamos las credenciales para el sistema nativo de Laravel
        // Laravel usará automáticamente 'getAuthPassword()' del modelo User para comparar el Hash
        $credentials = [
            'USU_CORREO' => $request->USU_CORREO,
            'password'   => $request->USU_CONTRASEÑA, // Se mapea como 'password' internamente para la verificación
        ];

        // 3. Intentamos iniciar sesión con Auth::attempt
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // 4. Redirección AUTOMÁTICA leyendo el rol directamente desde la Base de Datos
            if ($user->role) {
                if ($user->role->name === 'Secretaria') {
                    return redirect()->intended('/dashboard/secretaria'); // Dashboard de la Secretaria
                }
                
                if ($user->role->name === 'Rectora') {
                    return redirect()->intended('/dashboard/rectora'); // Dashboard de la Rectora
                }

                if ($user->role->name === 'Docente') {
                    return redirect()->intended('/dashboard/docente'); // Dashboard del Docente
                }
            }

            // Destino seguro por defecto por si el usuario tiene un rol diferente
            return redirect()->intended('/dashboard');
        }

        // 5. Si las credenciales fallan, regresamos con el mensaje de error estándar
        return back()->withErrors([
            'USU_CORREO' => 'El correo electrónico o la contraseña no coinciden con nuestros registros.',
        ])->withInput($request->only('USU_CORREO'));
    }

    /**
     * Cierra la sesión actual de forma segura.
>>>>>>> origin/backend-Elias
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}