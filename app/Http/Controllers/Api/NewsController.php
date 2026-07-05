<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNewsRequest;
use App\Http\Requests\UpdateNewsRequest;
use App\Services\NewsService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Http\Request;
class NewsController extends Controller
{
    public function __construct(private NewsService $service)
    {
    }

    public function index(Request $request)
    {
        try {
            // 1. Capturamos el parámetro 'nombre' (si no existe, será null)
            $request->query('title');
            $request->query('is_active');
            $news = $this->service->getAll($request);
            return $this->respondOk($news, "Noticias obtenidas exitosamente");
        } catch (\Exception $e) {
            return $this->parseException($e);
        }
    }

    public function getById(int $news_id): JsonResponse
    {
        try {
            // dd('tilina', $request);
            $news = $this->service->getById($news_id);
            return $this->respondOk($news, "Noticia encontrada exitosamente");
        } catch (\Exception $e) {
            return $this->parseException($e);
        }
    }

    public function store(StoreNewsRequest $request): JsonResponse
    {
        try {
            // dd('tilina', $request);
            $news = $this->service->store($request->validated());
            return $this->respondOk($news, "Noticia creada exitosamente");
        } catch (\Exception $e) {
            return $this->parseException($e);
        }
    }

    public function update(UpdateNewsRequest $request, int $news_id): JsonResponse
    {
        try {
            //  dd('tilina', $request);
            $news = $this->service->update($request->validated(), $news_id);
            return $this->respondOk($news, "Noticia actualizada exitosamente");
        } catch (\Exception $e) {
            return $this->parseException($e);
        }
    }
}
