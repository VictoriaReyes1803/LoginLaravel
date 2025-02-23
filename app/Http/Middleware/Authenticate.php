<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;  
use Illuminate\Http\Response;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function handle($request, Closure $next)
    {
        try {
            // Intenta obtener el usuario autenticado desde el token JWT
            $user = JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            // Si el token no es válido, regresa una respuesta 401
            return response()->json(['error' => 'Token no proporcionado o inválido'], 401);
        }

        // Si el usuario está autenticado y activo, pasa la solicitud
        return $next($request);
    
}

}
