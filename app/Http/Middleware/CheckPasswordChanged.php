<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPasswordChanged
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->must_change_password) {
            // Permitimos el acceso a las rutas de cambio de contraseña y al logout
            if (!$request->routeIs('perfil.password.*') && !$request->routeIs('logout')) {
                return redirect()->route('perfil.password.edit')
                    ->with('warning', 'Por seguridad, debes cambiar tu contraseña predeterminada antes de continuar.');
            }
        }

        return $next($request);
    }
}