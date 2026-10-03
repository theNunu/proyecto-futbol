<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Models\ErrorLog;
use Illuminate\Validation\ValidationException;

abstract class Controller
{
    use ApiResponse;

    protected function response($data, $message, $status = 200)
    {
        return response()->json([
            'message' => $message,
            'data' => $data
        ], $status);
    }

    protected function parseException(\Exception $e, $data = [])
    {

        $isDebug = config('app.debug');
        $message = $isDebug? $e->getMessage(): 'Ha ocurrido un error interno en el servidor.';

        $errorData = [];

        if($e instanceof ValidationException) {
            $errorData = [
                'errors' => $e->errors(),
                'input' => $data,
            ];
        } else {
            $errorData = [
                'error' => $e->getMessage(),
                'input' => $data,
            ];
        }

            // ErrorLog::create([
            //     'message' => 'Validation error',
            //     'exception' => get_class($e),
            //     'file' => $e->getFile(),
            //     'line' => $e->getLine(),
            //     'data' => $errorData,
            //     'created_at' => now(),
            // ]);

         $handlers = [
            \Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class     => fn($e) => $this->respondNotFound($e->getMessage()),
            \Illuminate\Database\Eloquent\ModelNotFoundException::class              => fn($e) => $this->respondNotFound($e->getMessage()),
            \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException::class => fn($e) => $this->respondUnauthorized($e->getMessage()),
            \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException::class => fn($e) => $this->respondAccessDenied($e->getMessage()),
            \Symfony\Component\HttpKernel\Exception\BadRequestHttpException::class   => fn($e) => $this->respondBadRequest(null, $e->getMessage()),
            \Illuminate\Validation\ValidationException::class                        => fn($e) => $this->respondUnprocessableEntity($e->errors(), $e->getMessage()),
            \Symfony\Component\HttpKernel\Exception\ConflictHttpException::class     => fn($e) => $this->respondConflict($e->getMessage()),
            // \Tymon\JWTAuth\Exceptions\TokenExpiredException::class                   => fn($e) => $this->respondUnauthorized('Token expirado, inicie sesión de nuevo'),
            // \Tymon\JWTAuth\Exceptions\TokenInvalidException::class                   => fn($e) => $this->respondUnauthorized('Token inválido'),
            \InvalidArgumentException::class                                         => fn($e) => $this->respondBadRequest(null, $e->getMessage()),

        ];

        foreach ($handlers as $type => $handler) {
            if ($e instanceof $type) {
                return $handler($e);
            }
        }
        

        return $this->respondServerError($message);
    }
}
