<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
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
     * Procesa la autenticación con Bloqueo Progresivo basado en Sesión
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'USU_CEDULA'     => 'required|string',
            'USU_CONTRASEÑA' => 'required|string',
        ], [
            'USU_CEDULA.required'     => 'El número de documento es obligatorio.',
            'USU_CONTRASEÑA.required' => 'La contraseña es obligatoria.',
        ]);

        // 1. Verificar si hay un bloqueo activo guardado en la sesión
        $lockoutUntil = session('lockout_until');
        if ($lockoutUntil) {
            $segundosRestantes = $lockoutUntil - now()->timestamp;

            if ($segundosRestantes > 0) {
                $tiempoFormateado = $this->formatearTiempoRestante($segundosRestantes);
                return back()->withErrors([
                    'USU_CEDULA' => "Demasiados intentos fallidos. Acceso bloqueado temporalmente. Inténtalo de nuevo en {$tiempoFormateado}.",
                ])->onlyInput('USU_CEDULA');
            } else {
                // El tiempo expiró, limpiamos la variable de bloqueo
                session()->forget('lockout_until');
            }
        }

        // 2. Buscar al usuario por su número de Cédula
        $user = User::where('USU_CEDULA', $credentials['USU_CEDULA'])->first();

        if (!$user) {
            $this->manejarIntentoFallido();
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
            
            // Acceso exitoso: limpiamos toda la data de seguridad de la sesión
            session()->forget(['login_attempts', 'login_level', 'lockout_until']);

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

        // 5. Si la contraseña falla, procesamos el fallo y escalamos el nivel
        $this->manejarIntentoFallido();

        return back()->withErrors([
            'USU_CONTRASEÑA' => 'La contraseña ingresada es incorrecta.',
        ])->onlyInput('USU_CEDULA');
    }

    /**
     * Gestiona las rondas e incrementa el tiempo de castigo según el nivel
     */
    private function manejarIntentoFallido()
    {
        $nivel = session('login_level', 1);
        $intentos = session('login_attempts', 0) + 1;

        // Primer nivel pide 5 intentos, los siguientes piden 3 intentos
        $intentosRequeridos = ($nivel === 1) ? 5 : 3;

        if ($intentos >= $intentosRequeridos) {
            $tiempoBloqueoSegundos = 60; // Nivel 1: 1 Minuto

            if ($nivel === 2) {
                $tiempoBloqueoSegundos = 600;   // Nivel 2: 10 Minutos
            } elseif ($nivel === 3) {
                $tiempoBloqueoSegundos = 3600;  // Nivel 3: 1 Hora
            } elseif ($nivel >= 4) {
                $tiempoBloqueoSegundos = 86400; // Nivel 4 en adelante: 1 Día
            }

            // Guardamos el timestamp exacto de expiración en la sesión
            session(['lockout_until' => now()->timestamp + $tiempoBloqueoSegundos]);

            // Reseteamos los intentos de la ronda y aumentamos el nivel para la próxima
            session(['login_attempts' => 0]);
            session(['login_level' => $nivel + 1]);
        } else {
            session(['login_attempts' => $intentos]);
        }
    }

    /**
     * Formatea los segundos restantes en un texto legible para el usuario
     */
    private function formatearTiempoRestante($segundos)
    {
        if ($segundos >= 86400) {
            return ceil($segundos / 86400) . ' día(s)';
        } elseif ($segundos >= 3600) {
            return ceil($segundos / 3600) . ' hora(s)';
        } elseif ($segundos >= 60) {
            return ceil($segundos / 60) . ' minuto(s)';
        }
        return $segundos . ' segundo(s)';
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