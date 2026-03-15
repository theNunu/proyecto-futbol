<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCatalogRequest;
use App\Services\CatalogService;
use Symfony\Component\HttpFoundation\JsonResponse;

class CatalogController extends Controller
{
    public function __construct(private CatalogService $service) {
    }

    public function index()
    {
        try {
            $tournaments = $this->service->getAll();
            return $this->respondOk($tournaments);
        } catch (\Exception $e) {
            return $this->parseException($e);
        }
    }

    public function store(StoreCatalogRequest $request): JsonResponse
    {
        try {
            // dd('tilina');
            $phase = $this->service->store($request->validated());
            return $this->respondOk($phase);
        } catch (\Exception $e) {
            return $this->parseException($e);
        }
    }

    public function update(StoreCatalogRequest $request, $phase_id): JsonResponse
    {
        try {
            $updated = $this->service->update($phase_id, $request->validated());
            return $this->respondOk($updated);
        } catch (\Exception $e) {
            return $this->parseException($e);
        }
    }
}
