<?php

use App\Http\Middleware\CheckOptionPermission;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Registrar el alias aquí
        $middleware->alias([
            'option' => CheckOptionPermission::class,

            // 2. Alias para el Middleware de JWT (elige el según el paquete que instalaste):
            // 'jwt' => \PHPOpenSourceSaver\JWTAuth\Http\Middleware\Authenticate::class,
            'jwt' => \Tymon\JWTAuth\Http\Middleware\Authenticate::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {

    //PARA CAPTURAR ERROES DE AUTNETICACION PARA LA PAPI SOLO QUE HAY QUE MOVER ESTE ARCHIVO (Y AL MUCHO EL MIDDLEWEARE)

        // 1. Forzar a que las excepciones en /api siempre devuelvan JSON limpio
        $exceptions->shouldRenderJsonWhen(function (Request $request, \Throwable $e) {
            return $request->is('api/*') || $request->wantsJson();
        });
        // 1. Error de JWT enviado con formato incorrecto, corrupto o ausente en el middleware
        $exceptions->render(function (UnauthorizedHttpException $e, Request $request) {
            return response()->json([
                'status_code' => 401,
                'success' => false,
                'message' => 'No autorizado: Token no proporcionado, inválido o mal formado.',
                'errors' => null
            ], 401);
        });

        // 2. Errores internos de JWT (expirado, firma inválida, etc.)
        $exceptions->render(function (JWTException $e, Request $request) {
            return response()->json([
                'status_code' => 401,
                'success' => false,
                'message' => 'No autorizado: ' . $e->getMessage(),
                'errors' => null
            ], 401);
        });

        // 3. Autenticación nativa de Laravel (por ejemplo, guard 'web' o 'sanctum')
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            return response()->json([
                'status_code' => 401,
                'success' => false,
                'message' => 'No autorizado: Sesión no válida o no autenticado.',
                'errors' => null
            ], 401);
        });

        // // 2. Capturar error 401 (Cuando falla JWT o Auth middleware)
        // $exceptions->render(function (AuthenticationException $e, Request $request) {
        //     return response()->json([
        //         'status_code' => 401,
        //         'success' => false,
        //         'message' => 'No autorizado: Token no proporcionado o inválido',
        //         'errors' => null
        //     ], 401);
        // });
    
        // // 3. Capturar error 403 (Permisos / Middleware 'option')
        // $exceptions->render(function (AccessDeniedHttpException $e, Request $request) {
        //     return response()->json([
        //         'status_code' => 403,
        //         'success' => false,
        //         'message' => 'Acceso denegado: No tienes los permisos requeridos',
        //         'errors' => null
        //     ], 403);
        // });
    
        // // 4. Capturar error 404 (Ruta no encontrada)
        // $exceptions->render(function (NotFoundHttpException $e, Request $request) {
        //     return response()->json([
        //         'status_code' => 404,
        //         'success' => false,
        //         'message' => 'El recurso o ruta solicitada no existe',
        //         'errors' => null
        //     ], 404);
        // });
        // //
        // // Captura errores de JWT malformado o no decodificable
        // $exceptions->render(function (UnauthorizedHttpException $e, $request) {
        //     if ($request->is('api/*') || $request->wantsJson()) {
        //         return response()->json([
        //             'status' => false,
        //             'message' => 'El token suministrado no es válido o está mal formado.',
        //             'error' => $e->getPrevious()?->getMessage() ?? $e->getMessage()
        //         ], 401);
        //     }
        // });
    
        // // Captura excepciones generales de JWT
        // $exceptions->render(function (JWTException $e, $request) {
        //     if ($request->is('api/*') || $request->wantsJson()) {
        //         return response()->json([
        //             'status' => false,
        //             'message' => 'Error al procesar el token de autenticación.',
        //             'error' => $e->getMessage()
        //         ], 401);
        //     }
        // });
    })->create();
