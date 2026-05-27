<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNewsRequest;
use App\Services\NewsService;
use Symfony\Component\HttpFoundation\JsonResponse;

class NewsController extends Controller
{
    public function __construct(private NewsService $service)
    {
    }

    public function index()
    {
        try {
            $news = $this->service->getAll();
            return $this->respondOk($news, "Noticias obtenidas exitosamente");
        } catch (\Exception $e) {
            return $this->parseException($e);
        }
    }

    public function store(StoreNewsRequest $request): JsonResponse
    {
        try {
            // dd('tilina');
            $news = $this->service->store($request->validated());
            return $this->respondOk($news, "Noticia creada exitosamente");
        } catch (\Exception $e) {
            return $this->parseException($e);
        }
    }
}
