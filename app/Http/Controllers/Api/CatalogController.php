<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCatalogDetailRequest;
use App\Http\Requests\StoreCatalogRequest;
use App\Services\CatalogService;
use Symfony\Component\HttpFoundation\JsonResponse;

class CatalogController extends Controller
{
    public function __construct(private CatalogService $service)
    {
    }

    public function index()
    {
        try {
            $catalogs = $this->service->getAll();
            return $this->respondOk($catalogs);
        } catch (\Exception $e) {
            return $this->parseException($e);
        }
    }

    public function store(StoreCatalogRequest $request): JsonResponse
    {
        try {
            // dd('tilina');
            $catalog = $this->service->store($request->validated());
            return $this->respondOk($catalog);
        } catch (\Exception $e) {
            return $this->parseException($e);
        }
    }

    public function update(StoreCatalogRequest $request, $phase_id): JsonResponse
    {  // ARREGLA ESTOOOOOOOOO
        try {
            $updated = $this->service->update($phase_id, $request->validated());
            return $this->respondOk($updated);
        } catch (\Exception $e) {
            return $this->parseException($e);
        }
    }

    public function createDetail(StoreCatalogDetailRequest $request)
    {
        try {
            $detail = $this->service->storeDetail($request->validated());
            return $this->respondOk($detail);
        } catch (\Exception $e) {
            return $this->parseException($e);
        }
    }

    public function getAllDetailsWithCatalog(){
        try {
            $tournaments = $this->service->getAllDetailsWithCatalog();
            return $this->respondOk($tournaments);
        } catch (\Exception $e) {
            return $this->parseException($e);
        }
    
    }
}
