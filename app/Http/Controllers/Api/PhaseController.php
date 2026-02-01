<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePhaseRequest;
use App\Services\PhaseService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class PhaseController extends Controller
{
    public function __construct(
        private PhaseService $service
    ) {
    }

    public function index(): JsonResponse
    {
        try {
            $tournaments = $this->service->getAll();
            return $this->respondOk($tournaments);
        } catch (\Exception $e) {
            return $this->parseException($e);
        }
    }

    public function getPhasesByTournamentId($tournament_id): JsonResponse
    {
        try {
            $tournaments = $this->service->getPhasesByTournamentId($tournament_id);
            return $this->respondOk($tournaments);
        } catch (\Exception $e) {
            return $this->parseException($e);
        }
    }

    public function store(StorePhaseRequest $request): JsonResponse
    {
        try {
            $phase = $this->service->store($request->validated());
            return $this->respondOk($phase);
        } catch (\Exception $e) {
            return $this->parseException($e);
        }
    }

    public function update(StorePhaseRequest $request, $phase_id): JsonResponse
    {
        try {
            $updated = $this->service->update($phase_id, $request->validated());
            return $this->respondOk($updated);
        } catch (\Exception $e) {
            return $this->parseException($e);
        }
    }

    // public function update(
    //     StorePhaseRequest $request,
    //     Phase $phase
    // ): JsonResponse {
    //     $updated = $this->service->update($phase, $request->validated());

    //     return response()->json($updated);
    // }

    // public function destroy(Phase $phase): JsonResponse
    // {
    //     $this->service->delete($phase);

    //     return response()->json(null, 204);
    // }
}
