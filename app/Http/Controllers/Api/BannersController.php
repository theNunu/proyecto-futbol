<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBannerRequest;
use App\Services\BannerService;
use App\Services\NewsService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class BannersController extends Controller
{
    public function __construct(private BannerService $service)
    {
    }

    public function index(Request $request)
    {
        try {
            // 1. Capturamos el parámetro 'nombre' (si no existe, será null)
            // $request->query('title');
            $request->query('is_active');
            $banners = $this->service->getAll($request);
            return $this->respondOk($banners, "Banners obtenidaos exitosamente");
        } catch (\Exception $e) {
            return $this->parseException($e);
        }
    }

    public function getById(int $banner_id): JsonResponse
    {
        try {
            // dd('tilina', $request);
            $banner = $this->service->getById($banner_id);
            return $this->respondOk($banner, "Banner encontrado exitosamente");
        } catch (\Exception $e) {
            return $this->parseException($e);
        }
    }

    public function store(StoreBannerRequest $request): JsonResponse
    {
        try {
            // dd('tilina', $request);
            $news = $this->service->store($request->validated());
            return $this->respondOk($news, "Banner creado exitosamente");
        } catch (\Exception $e) {
            return $this->parseException($e);
        }
    }

    public function update(StoreBannerRequest $request, int $news_id): JsonResponse
    {
        try {
            //  dd('tilina', $request);
            $news = $this->service->update($request->validated(), $news_id);
            return $this->respondOk($news, "Banner actualizado exitosamente");
        } catch (\Exception $e) {
            return $this->parseException($e);
        }
    }


    public function infoNews(Request $request): JsonResponse
    {
        try {


            // dd($filtros, $isActive, 'eso tilin');
            $filtros = $request->only(['name', 'description', 'is_active', 'p_category_id']);
            // dd('tilina', $request);

            $isActive = null;
            if ($request->has('is_active') && $request->input('is_active') !== '' && $request->input('is_active') !== null) {
                $isActive = $request->boolean('is_active'); // Retorna true o false booleano nativo
            }

            // dd($filtros, $isActive);


            $news = $this->service->infoNews($filtros);
            return $this->respondOk($news, "Noticia con filtros encontradas exitosamente");
        } catch (\Exception $e) {
            return $this->parseException($e);
        }
    }
}
