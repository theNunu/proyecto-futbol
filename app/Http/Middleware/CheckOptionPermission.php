<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckOptionPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$keys  Claves recibidas desde la ruta (ej. 'news_administrator')
     */
    public function handle(Request $request, Closure $next, string ...$keys): Response
    {
        $user = $request->user();

        // 1. Si no hay usuario autenticado, denegar acceso
        if (!$user) {
            return response()->json(['message' => 'No autenticado.'], 401);
        }

        // 2. Consultar si el usuario tiene al menos una de las claves requeridas
        // Aprovecha la relación $user->roles()->with('menus_options')
        $hasPermission = $user->roles()
            ->where('is_active', true)
            ->whereHas('menus_options', function ($query) use ($keys) {
                $query->whereIn('key', $keys)
                    ->where('is_active', true);
            })
            ->exists();

        // 3. Si no tiene el permiso, retornar respuesta 403 (Prohibido)
        if (!$hasPermission) {
            return response()->json([
                'message' => 'No tienes permisos para realizar esta acción.'
            ], 403);
        }

        // 4. Si pasa la validación, la petición continúa al controlador
        return $next($request);
    }
}
