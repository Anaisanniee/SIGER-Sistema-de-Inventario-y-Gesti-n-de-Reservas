<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Maneja las peticiones entrantes y valida el rol del usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles Lista de roles permitidos (Ej: 'Rectora', 'Secretaria')
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Si el usuario ni siquiera ha iniciado sesión, lo mandamos al Login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // 2. Si por alguna razón el usuario no tiene un rol asignado en la BD, afuera
        if (!$user->role) {
            return redirect('/')->with('error', 'Tu cuenta no tiene un rol asignado en el sistema.');
        }

        // 3. Recorremos los roles permitidos en la ruta y los comparamos con el de la BD
        // Recuerda que en el Seeder los guardamos con Mayúscula Inicial: 'Rectora', 'Secretaria'
        foreach ($roles as $role) {
            if ($user->role->name === $role) {
                return $next($request); // ¡Tiene permiso! Continúa hacia la ruta
            }
        }

        // 4. Si termina el ciclo y no coincidió con ningún rol, denegamos el acceso
        return redirect('/')->with('error', 'No tienes permisos de acceso para esta sección.');
    }
}