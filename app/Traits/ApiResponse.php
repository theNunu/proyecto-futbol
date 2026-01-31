<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponse
{
    /**
     * Retorna un JsonResponse con status 200 (OK)
     *
     * @param mixed|null $data Datos a enviar
     * @param string $message Mensaje opcional
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondOk($data = null, $message = 'Solicitud realizada correctamente'): JsonResponse
    {
        return $this->responseWithoutError(Response::HTTP_OK, $message, $data);
    }

    /**
     * Retorna un JsonResponse con status 201 (Created)
     *
     * @param mixed|null $data Datos a enviar
     * @param string $message Mensaje opcional
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondCreated($data = null, $message = 'Creado exitoso'): JsonResponse
    {
        return $this->responseWithoutError(Response::HTTP_CREATED, $message, $data);
    }

    /**
     * Retorna un JsonResponse con status 404 (Not Found)
     *
     * @param string $message Mensaje opcional
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondNotFound($message = 'Recurso no encontrado'): JsonResponse
    {
        return $this->responseWithoutError(Response::HTTP_NOT_FOUND, $message);
    }

    /**
     * Retorna un JsonResponse con status 400 (Bad Request)
     *
     * @param mixed|null $data Datos de error opcionales
     * @param string $message Mensaje opcional
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondBadRequest($data = null, $message = 'Solicitud mal formada'): JsonResponse
    {
        return $this->responseWithError(Response::HTTP_BAD_REQUEST, $message, $data);
    }

    /**
     * Retorna un JsonResponse con error 401 (Unauthorized)
     *
     * @param string $message Mensaje opcional
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondUnauthorized($message = 'No autorizado'): JsonResponse
    {
        return $this->responseWithError(Response::HTTP_UNAUTHORIZED, $message);
    }

    /**
     * Retorna un JsonResponse con error 403 (Forbidden)
     *
     * @param string $message Mensaje opcional
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondAccessDenied($message = 'El acceso al recurso está prohibido'): JsonResponse
    {
        return $this->responseWithError(Response::HTTP_FORBIDDEN, $message);
    }

    /**
     * Retorna un JsonResponse con error 422 (Unprocessable Entity)
     *
     * @param mixed|null $data Datos de error opcionales
     * @param string $message Mensaje opcional
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondUnprocessableEntity($data = null, $message = 'Datos inválidos'): JsonResponse
    {
        return $this->responseWithError(Response::HTTP_UNPROCESSABLE_ENTITY, $message, $data);
    }

    /**
     * Retorna un JsonResponse con error 500 (Internal Server Error)
     *
     * @param string $message Mensaje opcional
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondServerError($message = 'Error interno del servidor'): JsonResponse
    {
        return $this->responseWithError(Response::HTTP_INTERNAL_SERVER_ERROR, $message);
    }

    /**
     * Retorna un JsonResponse con conflicto 409 (Conflicto)
     *
     * @param string $message Mensaje opcional
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondConflict($message = 'Ya existe una operación en curso. Inténtelo más tarde.'): JsonResponse
    {
        return $this->responseWithError(Response::HTTP_CONFLICT, $message);
    }

    /**
     * Retorna un JsonResponse con datos y sin errores
     *
     * @param int $statusCode Código HTTP
     * @param string $message Mensaje opcional
     * @param mixed|null $data Datos a enviar
     * @return \Illuminate\Http\JsonResponse
     */
    private function responseWithoutError($statusCode, $message, $data = null)
    {
        return response()->json(
            [
                'status_code' => $statusCode,
                'success' => true,
                'message' => $message,
                'data' => $data
            ],
            $statusCode
        );
    }

    /**
     * Retorna un JsonResponse con datos de error
     *
     * @param int $statusCode Código HTTP
     * @param string $message Mensaje de error
     * @param mixed|null $data Datos de error opcionales
     * @return \Illuminate\Http\JsonResponse
     */
    private function responseWithError($statusCode, $message, $data = null)
    {
        return response()->json(
            [
                'status_code' => $statusCode,
                'success' => false,
                'message' => $message,
                'errors' => $data
            ],
            $statusCode
        );
    }
}
