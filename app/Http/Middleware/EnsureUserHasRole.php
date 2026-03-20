<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        // Verificar si el usuario está autenticado y tiene el rol requerido
        if ($request->user() && $request->user()->role == $role) {
            return $next($request);
        }

        // Si no tiene el rol requerido, devolver un error 403 (Prohibido)
        abort(403, 'No tienes permiso para acceder a esta página.');
    }
}