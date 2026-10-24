<?php

namespace App\Admin\AdministrativeManagement\Controllers;

use App\Admin\AdministrativeManagement\Requests\StoreBannerRequest;
use App\Admin\AdministrativeManagement\Services\BannerService;
use App\Http\Controllers\Controller;
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

    public function update(StoreBannerRequest $request, int $banner_id): JsonResponse
    {
        try {
            //  dd('tilina', $request);
            $news = $this->service->update($request->validated(), $banner_id);
            return $this->respondOk($news, "Banner actualizado exitosamente");
        } catch (\Exception $e) {
            return $this->parseException($e);
        }
    }


}
