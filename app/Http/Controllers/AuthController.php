<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Muestra el formulario de login (que harán los del frontend)
    public function showLogin()
    {
        return view('auth.login');
    }

    // Procesa el intento de entrada con las nuevas columnas
   public function login(Request $request)
{
    // 1. Validamos que lleguen los campos correctos
    $credentials = $request->validate([
        'USU_CORREO' => 'required|email',
        'USU_CONTRASEÑA' => 'required',
        'rol_name' => 'required' // El texto que viene del formulario
    ]);

    // 2. Buscamos al usuario por su correo electrónico
    $user = \App\Models\User::where('USU_CORREO', $request->USU_CORREO)->first();

    // 3. Verificamos si el usuario existe y si la contraseña coincide
    // (Nota: si usas Hash::check para contraseñas encriptadas, úsalo aquí. Si es texto plano por ahora, déjalo directo)
    if ($user && $user->USU_CONTRASEÑA === $request->USU_CONTRASEÑA) {
        
        // 4. Buscamos el nombre del rol del usuario cruzando las tablas
        $userRolName = $user->role->name; // Asumiendo que tienes la relación 'role' en tu modelo User

        // 5. Comparamos el rol de la base de datos con el que seleccionaron en el formulario
        if ($userRolName === $request->rol_name) {
            
            // Si todo coincide, iniciamos la sesión
            auth()->login($user);
            $request->session()->regenerate();

            // Redirección según el rol
            if ($userRolName === 'Secretaria') {
                return redirect()->intended('/usuarios');
            }
            
            return redirect()->intended('/dashboard');
        }
    }

    // Si algo falla, lo regresamos con error
    return back()->withErrors([
        'USU_CORREO' => 'Las credenciales o el rol seleccionado no coinciden con nuestros registros.',
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