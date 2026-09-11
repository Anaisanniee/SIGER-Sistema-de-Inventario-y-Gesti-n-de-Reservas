<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use App\Mail\NuevoDispositivoMail;

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
            
            session()->forget(['login_attempts', 'login_level', 'lockout_until']);

            Auth::login($user);
            $request->session()->regenerate();

            // 4.1. Verificación de cambio de contraseña obligatorio (Secretaría inicial u otros)
            if (isset($user->must_change_password) && $user->must_change_password) {
                return redirect()->route('perfil.password.edit')
                    ->with('warning', 'Por seguridad, debes cambiar tu contraseña predeterminada antes de continuar.');
            }

            // 4.2. Detección de nuevo dispositivo con bloqueo estricto (Omitir para la secretaría)
            $rolName = strtolower($user->role->name ?? '');
            $rolSlug = strtolower($user->role->slug ?? '');
            $esSecretaria = in_array($rolName, ['secretaria', 'secretario']) || in_array($rolSlug, ['secretaria', 'secretario']) || ($user->USU_CORREO === 'secretaria@siger.edu.co');

            if (!$esSecretaria) {
                $ip = $request->ip();
                $userAgent = $request->header('User-Agent');

                $dispositivoRegistrado = DB::table('user_devices')
                    ->where('user_id', $user->getKey())
                    ->where('ip_address', $ip)
                    ->where('user_agent', $userAgent)
                    ->exists();

                if (!$dispositivoRegistrado) {
                    $correoDestino = $user->USU_CORREO ?? $user->email ?? null;

                    if ($correoDestino) {
                        Mail::to($correoDestino)->send(new NuevoDispositivoMail($user, $ip, $userAgent));
                    }

                    // Frenamos el acceso y destruimos la sesión temporal de login
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    return redirect()->route('device.verify.notice');
                }
            }

            // 5. Redireccionar al dashboard según el Rol asignado
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
     * Registra el dispositivo cuando el usuario hace clic en el enlace firmado del correo
     */
    public function autorizarDispositivo(Request $request)
    {
        $userId = $request->query('user');
        $ip = $request->query('ip');
        $userAgent = $request->query('user_agent');

        $existe = DB::table('user_devices')
            ->where('user_id', $userId)
            ->where('ip_address', $ip)
            ->where('user_agent', $userAgent)
            ->exists();

        if (!$existe) {
            DB::table('user_devices')->insert([
                'user_id'    => $userId,
                'ip_address' => $ip,
                'user_agent' => $userAgent,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return view('auth.dispositivo-autorizado');
    }

   /**
     * Verifica mediante AJAX si el dispositivo actual ya fue autorizado analizando la IP y el User-Agent
     */
    public function verificarEstadoDispositivo(Request $request)
    {
        $ip = $request->ip();
        $userAgent = $request->header('User-Agent');

        // Buscamos si ya existe un registro para este dispositivo en la base de datos
        $dispositivo = DB::table('user_devices')
            ->where('ip_address', $ip)
            ->where('user_agent', $userAgent)
            ->latest('updated_at')
            ->first();

        if ($dispositivo) {
            // Encontramos el dispositivo registrado, procedemos a loguear al usuario
            $user = User::find($dispositivo->user_id);
            
            if ($user) {
                Auth::login($user);
                request()->session()->regenerate();

                // Determinamos la ruta de redirección según su rol
                $rolName = strtolower($user->role->name ?? '');
                $rolSlug = strtolower($user->role->slug ?? '');
                $redirectUrl = route('dashboard.secretaria');

                if (in_array($rolName, ['rectora', 'rector']) || in_array($rolSlug, ['rectora', 'rector'])) {
                    $redirectUrl = route('dashboard.rectora');
                } elseif (in_array($rolName, ['secretaria', 'secretario']) || in_array($rolSlug, ['secretaria', 'secretario'])) {
                    $redirectUrl = route('dashboard.secretaria');
                } elseif ($rolName === 'docente' || $rolSlug === 'docente') {
                    $redirectUrl = route('dashboard.docente');
                }

                return response()->json([
                    'autorizado' => true,
                    'redirect'   => $redirectUrl
                ]);
            }
        }

        return response()->json(['autorizado' => false]);
    }

    /**
     * Gestiona las rondas e incrementa el tiempo de castigo según el nivel
     */
    private function manejarIntentoFallido()
    {
        $nivel = session('login_level', 1);
        $intentos = session('login_attempts', 0) + 1;

        $intentosRequeridos = ($nivel === 1) ? 5 : 3;

        if ($intentos >= $intentosRequeridos) {
            $tiempoBloqueoSegundos = 60;

            if ($nivel === 2) {
                $tiempoBloqueoSegundos = 600;
            } elseif ($nivel === 3) {
                $tiempoBloqueoSegundos = 3600;
            } elseif ($nivel >= 4) {
                $tiempoBloqueoSegundos = 86400;
            }

            session(['lockout_until' => now()->timestamp + $tiempoBloqueoSegundos]);
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