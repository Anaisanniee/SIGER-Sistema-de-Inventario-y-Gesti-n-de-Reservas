<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
 public function handle(Request $request, Closure $next, ...$roles)
{
    // Si el usuario no está logueado, al login
    if (!Auth::check()) {
        return redirect('login');
    }

    $user = Auth::user();
    
    // Si el rol del usuario está en la lista permitida, pasa.
    // Recuerda: 1=Rectora, 2=Secretaria, 3=Docente
    foreach ($roles as $role) {
        if ($user->role_id == $role) {
            return $next($request);
        }
    }

    // Si no tiene permiso, lo mandamos afuera con un mensaje
    return redirect('/')->with('error', 'No tienes permisos para acceder a esta sección.');
}
}
